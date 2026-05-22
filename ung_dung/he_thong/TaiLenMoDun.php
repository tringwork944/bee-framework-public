<?php
declare(strict_types=1);

namespace HeThong;

use RuntimeException;
use ZipArchive;

class TaiLenMoDun
{
    private const DUNG_LUONG_TOI_DA = 10485760;
    private const MIME_CHO_PHEP = [
        'application/zip',
        'application/x-zip-compressed',
        'application/octet-stream',
    ];

    /**
     * @param array<string, mixed> $tepTaiLen
     * @return array{ma:string, thu_muc:string}
     */
    public function xuLy(array $tepTaiLen): array
    {
        $this->kiemTraQuyen();
        $this->kiemTraUpload($tepTaiLen);
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('May chu chua ho tro ZipArchive de tai mo dun.');
        }

        $tepTamZip = '';
        $thuMucLamViec = '';

        try {
            $thuMucGocTam = GOC_DU_AN . '/ung_dung/kho_luu/tam/mo_dun';
            $this->taoThuMucNeuCan($thuMucGocTam);

            $duoiFile = strtolower(pathinfo((string)$tepTaiLen['name'], PATHINFO_EXTENSION));
            if ($duoiFile !== 'zip') {
                throw new RuntimeException('Chi chap nhan file .zip.');
            }

            $tepTamZip = $thuMucGocTam . '/upload_' . bin2hex(random_bytes(8)) . '.zip';
            if (!move_uploaded_file((string)$tepTaiLen['tmp_name'], $tepTamZip)) {
                throw new RuntimeException('Khong the luu tep tai len tam thoi.');
            }

            $thuMucLamViec = $thuMucGocTam . '/giai_nen_' . bin2hex(random_bytes(8));
            $thuMucGiaiNen = $thuMucLamViec . '/extract';
            $this->taoThuMucNeuCan($thuMucGiaiNen);

            $zip = new ZipArchive();
            if ($zip->open($tepTamZip) !== true) {
                throw new RuntimeException('Khong mo duoc file zip.');
            }

            try {
                $this->giaiNenAnToan($zip, $thuMucGiaiNen);
            } finally {
                $zip->close();
            }

            $thuMucGocMoDun = $this->xacDinhThuMucGocMoDun($thuMucGiaiNen);
            $duongDanCauHinh = $thuMucGocMoDun . '/cau_hinh.php';
            $ma = $this->docMaMoDunTuCauHinh($duongDanCauHinh);
            $this->kiemTraMaMoDun($ma);

            if (in_array($ma, QuanLyMoDun::MO_DUN_LOI, true)) {
                throw new RuntimeException('Khong duoc tai len de ghi de mo dun loi.');
            }

            $thuMucDich = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
            if (is_dir($thuMucDich)) {
                throw new RuntimeException('Mo dun `' . $ma . '` da ton tai. Vui long xoa hoac doi goi zip khac.');
            }

            $this->diChuyenMoDun($thuMucGocMoDun, $thuMucDich);

            $quanLyMoDun = new QuanLyMoDun();
            $quanLyMoDun->dongBo();

            return ['ma' => $ma, 'thu_muc' => $thuMucDich];
        } finally {
            if ($tepTamZip !== '' && is_file($tepTamZip)) {
                @unlink($tepTamZip);
            }
            if ($thuMucLamViec !== '' && is_dir($thuMucLamViec)) {
                $this->xoaThuMucDeQuy($thuMucLamViec);
            }
        }
    }

    private function kiemTraQuyen(): void
    {
        if (!co_quyen('quan_ly_mo_dun.tai_len')) {
            throw new RuntimeException('Ban khong co quyen tai mo dun.');
        }
    }

    /**
     * @param array<string, mixed> $tepTaiLen
     */
    private function kiemTraUpload(array $tepTaiLen): void
    {
        if ($tepTaiLen === [] || !isset($tepTaiLen['error'])) {
            throw new RuntimeException('Vui long chon file zip mo dun.');
        }

        $maLoi = (int)$tepTaiLen['error'];
        if ($maLoi !== UPLOAD_ERR_OK) {
            throw new RuntimeException(match ($maLoi) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File tai len vuot qua gioi han cho phep.',
                UPLOAD_ERR_PARTIAL => 'File tai len chua hoan tat.',
                UPLOAD_ERR_NO_FILE => 'Vui long chon file zip mo dun.',
                default => 'Tai len file that bai.',
            });
        }

        $kichThuoc = (int)($tepTaiLen['size'] ?? 0);
        if ($kichThuoc <= 0) {
            throw new RuntimeException('File tai len khong hop le.');
        }
        if ($kichThuoc > self::DUNG_LUONG_TOI_DA) {
            throw new RuntimeException('File zip vuot qua gioi han 10MB.');
        }

        $tenGoc = (string)($tepTaiLen['name'] ?? '');
        if (strtolower(pathinfo($tenGoc, PATHINFO_EXTENSION)) !== 'zip') {
            throw new RuntimeException('Chi chap nhan file .zip.');
        }

        $tepTmp = (string)($tepTaiLen['tmp_name'] ?? '');
        if ($tepTmp === '' || !is_uploaded_file($tepTmp)) {
            throw new RuntimeException('Khong xac thuc duoc file tai len.');
        }

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mime = (string)finfo_file($finfo, $tepTmp);
                finfo_close($finfo);
                if ($mime !== '' && !in_array($mime, self::MIME_CHO_PHEP, true)) {
                    throw new RuntimeException('MIME file zip khong hop le.');
                }
            }
        }
    }

    private function taoThuMucNeuCan(string $thuMuc): void
    {
        if (is_dir($thuMuc)) {
            return;
        }
        if (!mkdir($thuMuc, 0775, true) && !is_dir($thuMuc)) {
            throw new RuntimeException('Khong tao duoc thu muc tam de xu ly upload.');
        }
    }

    private function giaiNenAnToan(ZipArchive $zip, string $thuMucDich): void
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $thongTin = $zip->statIndex($i);
            if (!is_array($thongTin)) {
                throw new RuntimeException('Khong doc duoc thong tin zip.');
            }

            $tenTrongZip = (string)($thongTin['name'] ?? '');
            if ($tenTrongZip === '') {
                continue;
            }

            $duongDanTuongDoi = $this->chuanHoaVaKiemTraDuongDanZip($tenTrongZip);
            if ($duongDanTuongDoi === '') {
                continue;
            }

            if ($this->laSymlinkTrongZip($zip, $i)) {
                throw new RuntimeException('Khong chap nhan symlink trong goi mo dun.');
            }

            $duongDanDich = $thuMucDich . '/' . $duongDanTuongDoi;
            if (str_ends_with($tenTrongZip, '/')) {
                $this->taoThuMucNeuCan($duongDanDich);
                continue;
            }

            $thuMucCha = dirname($duongDanDich);
            $this->taoThuMucNeuCan($thuMucCha);

            $thuMucDichThuc = realpath($thuMucDich);
            $thuMucChaThuc = realpath($thuMucCha);
            if ($thuMucDichThuc === false || $thuMucChaThuc === false || !str_starts_with($thuMucChaThuc, $thuMucDichThuc)) {
                throw new RuntimeException('Phat hien duong dan giai nen khong an toan.');
            }

            $stream = $zip->getStream($tenTrongZip);
            if (!is_resource($stream)) {
                throw new RuntimeException('Khong doc duoc noi dung file trong zip.');
            }

            $tepDich = fopen($duongDanDich, 'wb');
            if ($tepDich === false) {
                fclose($stream);
                throw new RuntimeException('Khong ghi duoc file da giai nen.');
            }

            stream_copy_to_stream($stream, $tepDich);
            fclose($stream);
            fclose($tepDich);
        }
    }

    private function chuanHoaVaKiemTraDuongDanZip(string $tenTrongZip): string
    {
        $tenTrongZip = str_replace('\\', '/', $tenTrongZip);
        if (str_starts_with($tenTrongZip, '/')) {
            throw new RuntimeException('Zip chua duong dan tuyet doi khong hop le.');
        }

        $thanhPhan = array_values(array_filter(explode('/', $tenTrongZip), static fn($phan) => $phan !== ''));
        if ($thanhPhan === []) {
            return '';
        }

        $chuanHoa = [];
        foreach ($thanhPhan as $phan) {
            if ($phan === '.' || $phan === '..' || str_contains($phan, '..')) {
                throw new RuntimeException('Zip chua duong dan traversal khong hop le.');
            }
            if (in_array($phan, ['ung_dung', 'cong_khai'], true)) {
                throw new RuntimeException('Zip chua duong dan bi cam.');
            }
            if (in_array(strtolower($phan), ['.env', '.htaccess'], true)) {
                throw new RuntimeException('Zip chua file nguy hiem khong duoc phep.');
            }
            $chuanHoa[] = $phan;
        }

        if (count($chuanHoa) === 1 && strtolower($chuanHoa[0]) === 'index.php') {
            throw new RuntimeException('Zip chua file nguy hiem khong duoc phep.');
        }

        return implode('/', $chuanHoa);
    }

    private function laSymlinkTrongZip(ZipArchive $zip, int $chiSo): bool
    {
        $opsys = 0;
        $attr = 0;
        if (!$zip->getExternalAttributesIndex($chiSo, $opsys, $attr)) {
            return false;
        }

        $cheDo = ($attr >> 16) & 0xF000;
        return $cheDo === 0xA000;
    }

    private function xacDinhThuMucGocMoDun(string $thuMucGiaiNen): string
    {
        if (is_file($thuMucGiaiNen . '/cau_hinh.php') && is_file($thuMucGiaiNen . '/mo_dun.php')) {
            return $thuMucGiaiNen;
        }

        $ungVien = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($thuMucGiaiNen, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if (!$item->isDir()) {
                continue;
            }
            $thuMuc = $item->getPathname();
            if (is_file($thuMuc . '/cau_hinh.php') && is_file($thuMuc . '/mo_dun.php')) {
                $ungVien[] = $thuMuc;
            }
        }

        $ungVien = array_values(array_unique($ungVien));
        if (count($ungVien) !== 1) {
            throw new RuntimeException('Khong xac dinh duoc thu muc goc mo dun hop le trong file zip.');
        }

        return $ungVien[0];
    }

    private function docMaMoDunTuCauHinh(string $duongDanCauHinh): string
    {
        if (!is_file($duongDanCauHinh)) {
            throw new RuntimeException('Thieu file cau_hinh.php trong goi mo dun.');
        }

        $noiDung = (string)file_get_contents($duongDanCauHinh);
        if ($noiDung === '') {
            throw new RuntimeException('Khong doc duoc file cau_hinh.php.');
        }

        if (!preg_match('/[\'"]ma[\'"]\s*=>\s*[\'"]([a-z0-9_]+)[\'"]/', $noiDung, $khop)) {
            throw new RuntimeException('Khong doc duoc ma mo dun tu cau_hinh.php.');
        }

        return (string)$khop[1];
    }

    private function kiemTraMaMoDun(string $ma): void
    {
        if (!preg_match('/^[a-z0-9_]+$/', $ma)) {
            throw new RuntimeException('Ma mo dun trong file zip khong hop le.');
        }
    }

    private function diChuyenMoDun(string $thuMucNguon, string $thuMucDich): void
    {
        $this->taoThuMucNeuCan(dirname($thuMucDich));
        if (@rename($thuMucNguon, $thuMucDich)) {
            return;
        }

        $this->saoChepThuMucDeQuy($thuMucNguon, $thuMucDich);
    }

    private function saoChepThuMucDeQuy(string $nguon, string $dich): void
    {
        $this->taoThuMucNeuCan($dich);
        $items = scandir($nguon);
        if ($items === false) {
            throw new RuntimeException('Khong doc duoc thu muc mo dun da giai nen.');
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $duongDanNguon = $nguon . '/' . $item;
            $duongDanDich = $dich . '/' . $item;
            if (is_dir($duongDanNguon)) {
                $this->saoChepThuMucDeQuy($duongDanNguon, $duongDanDich);
                continue;
            }

            if (!copy($duongDanNguon, $duongDanDich)) {
                throw new RuntimeException('Khong the di chuyen mo dun vao thu muc dich.');
            }
        }
    }

    private function xoaThuMucDeQuy(string $thuMuc): void
    {
        $items = scandir($thuMuc);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $duongDan = $thuMuc . '/' . $item;
            if (is_dir($duongDan) && !is_link($duongDan)) {
                $this->xoaThuMucDeQuy($duongDan);
                continue;
            }

            @unlink($duongDan);
        }

        @rmdir($thuMuc);
    }
}
