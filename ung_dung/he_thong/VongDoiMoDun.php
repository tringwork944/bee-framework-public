<?php
declare(strict_types=1);

namespace HeThong;

use FilesystemIterator;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class VongDoiMoDun
{
    private const PHIEN_BAN_CORE = '1.0.0';
    private QuanLyMoDun $quanLyMoDun;
    private QuanLyMenu $quanLyMenu;

    public function __construct(?QuanLyMoDun $quanLyMoDun = null, ?QuanLyMenu $quanLyMenu = null)
    {
        $this->quanLyMoDun = $quanLyMoDun ?? new QuanLyMoDun();
        $this->quanLyMenu = $quanLyMenu ?? new QuanLyMenu(new KiemTraQuyen());
    }

    public function caiDat(string $ma): array
    {
        return $this->thucThiAnToan('mo_dun.truoc_cai_dat', 'mo_dun.sau_cai_dat', function () use ($ma) {
            $thongTin = $this->timMoDun($ma);
            if ($thongTin['trang_thai'] !== 'chua_cai_dat' && $thongTin['trang_thai'] !== 'loi') {
                return ['ok' => false, 'thong_bao' => 'Mo dun da cai dat.'];
            }
            $this->kiemTraYeuCau($ma);
            $this->kiemTraPhuThuocBatBuoc($ma);

            $thuMuc = $this->quanLyMoDun->duongDanMoDun($ma);
            $this->chayTepSqlNeuCo($thuMuc . '/co_so_du_lieu/migration.sql');
            $this->chayTepSqlNeuCo($thuMuc . '/co_so_du_lieu/seed.sql');
            $this->chayFileVongDoi($thuMuc, 'cai_dat.php', $ma);
            $this->dongBoQuyen($ma);
            $this->capNhatTrangThai($ma, 'da_cai_dat', ['ngay_cai_dat' => 'NOW()']);
            $this->dongBoMenu($ma);

            return ['ok' => true, 'thong_bao' => 'Da cai dat mo dun.'];
        }, $ma);
    }

    public function kichHoat(string $ma): array
    {
        return $this->thucThiAnToan('mo_dun.truoc_kich_hoat', 'mo_dun.sau_kich_hoat', function () use ($ma) {
            $thongTin = $this->timMoDun($ma);
            if ($thongTin['trang_thai'] === 'dang_bat') {
                return ['ok' => true, 'thong_bao' => 'Mo dun da dang bat.'];
            }
            if ($thongTin['trang_thai'] === 'chua_cai_dat') {
                return ['ok' => false, 'thong_bao' => 'Can cai dat truoc khi kich hoat.'];
            }
            $this->kiemTraPhuThuocBatBuoc($ma);
            $thuMuc = $this->quanLyMoDun->duongDanMoDun($ma);
            $this->chayFileVongDoi($thuMuc, 'kich_hoat.php', $ma);
            if ($this->laMoDunGiaoDien($ma)) {
                $this->tatGiaoDienKhac($ma);
                QuanLyGiaoDien::kichHoatTheoMoDun($ma);
            }
            $this->capNhatTrangThaiMenu($ma, 1);
            $this->capNhatTrangThai($ma, 'dang_bat', ['ngay_kich_hoat' => 'NOW()']);
            return ['ok' => true, 'thong_bao' => 'Da kich hoat mo dun.'];
        }, $ma);
    }

    public function tat(string $ma): array
    {
        return $this->thucThiAnToan('mo_dun.truoc_tat', 'mo_dun.sau_tat', function () use ($ma) {
            $thongTin = $this->timMoDun($ma);
            if ((bool)$thongTin['la_mo_dun_loi']) {
                return ['ok' => false, 'thong_bao' => 'Khong the tat mo dun loi'];
            }
            $phuThuocNguoc = $this->quanLyMoDun->kiemTraMoDunPhuThuocDangBat($ma);
            if ($phuThuocNguoc !== []) {
                return ['ok' => false, 'thong_bao' => 'Khong the tat. Mo dun dang duoc phu thuoc: ' . implode(', ', $phuThuocNguoc)];
            }
            $thuMuc = $this->quanLyMoDun->duongDanMoDun($ma);
            $this->chayFileVongDoi($thuMuc, 'tat.php', $ma);
            $this->capNhatTrangThaiMenu($ma, 0);
            $this->capNhatTrangThai($ma, 'dang_tat', ['ngay_tat' => 'NOW()']);
            return ['ok' => true, 'thong_bao' => 'Da tat mo dun.'];
        }, $ma);
    }

    public function goCaiDat(string $ma): array
    {
        try {
            $thongTin = $this->timMoDun($ma);
            if ((bool)$thongTin['la_mo_dun_loi'] || in_array($ma, QuanLyMoDun::MO_DUN_LOI, true)) {
                return ['ok' => false, 'thong_bao' => 'Khong the go cai dat mo dun loi.'];
            }
            if ($thongTin['trang_thai'] === 'dang_bat') {
                return ['ok' => false, 'thong_bao' => 'Mo dun dang bat. Vui long tat mo dun truoc khi go cai dat.'];
            }
            if ($thongTin['trang_thai'] !== 'dang_tat') {
                return ['ok' => false, 'thong_bao' => 'Chi duoc go cai dat mo dun o trang thai dang tat.'];
            }

            $phuThuocNguoc = $this->quanLyMoDun->kiemTraMoDunPhuThuocDaCaiDat($ma);
            if ($phuThuocNguoc !== []) {
                return ['ok' => false, 'thong_bao' => 'Khong the go cai dat. Mo dun dang duoc phu thuoc: ' . implode(', ', $phuThuocNguoc)];
            }

            $pdo = CoSoDuLieu::layKetNoi();
            $thuMuc = $this->quanLyMoDun->duongDanMoDun($ma);
            $duongDanSql = $this->duongDanSqlGoCaiDat($thuMuc);
            $pdo->beginTransaction();
            try {
                $this->chayFileVongDoi($thuMuc, 'go_cai_dat.php', $ma);
                $this->chaySqlFile($duongDanSql);
                $this->xoaDuLieuHeThongMoDun($ma);
                $this->xoaBanGhiMoDun($ma);
                $pdo->commit();
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $e;
            }

            try {
                $this->xoaThuMucBangPhp($thuMuc);
                $this->ghiNhatKyGoCaiDat($ma, true);
                return ['ok' => true, 'thong_bao' => 'Da go cai dat mo dun va xoa thu muc ma nguon.'];
            } catch (Throwable $e) {
                $this->ghiNhatKyGoCaiDat($ma, false, $e->getMessage());
                return [
                    'ok' => false,
                    'thong_bao' => 'Da go cai dat mo dun khoi CSDL nhung khong the xoa thu muc ma nguon: ' . $e->getMessage(),
                ];
            }
        } catch (Throwable $e) {
            return ['ok' => false, 'thong_bao' => $e->getMessage()];
        }
    }

    public function kiemTra(string $ma): array
    {
        $thongTin = $this->timMoDun($ma);
        $thuMuc = $this->quanLyMoDun->duongDanMoDun($ma);
        $loi = [];
        foreach (['cau_hinh.php', 'mo_dun.php', 'cai_dat.php', 'kich_hoat.php', 'tat.php', 'go_cai_dat.php'] as $tep) {
            if (!is_file($thuMuc . '/' . $tep)) {
                if ((bool)$thongTin['la_mo_dun_loi']) {
                    $loi[] = 'Mo dun loi thieu file lifecycle: ' . $tep;
                } else {
                    $loi[] = 'Thieu ' . $tep;
                }
            }
        }
        if ($this->quanLyMoDun->kiemTraPhuThuoc($ma) !== []) {
            $loi[] = 'Thieu phu thuoc dang bat.';
        }
        return ['ok' => $loi === [], 'loi' => $loi, 'mo_dun' => $thongTin];
    }

    private function timMoDun(string $ma): array
    {
        if (!preg_match('/^[a-z0-9_]+$/', $ma)) {
            throw new \RuntimeException('Ma mo dun khong hop le.');
        }
        $thongTin = $this->quanLyMoDun->thongTinMoDun($ma);
        if ($thongTin === null) {
            throw new \RuntimeException('Khong tim thay mo dun.');
        }
        return $thongTin;
    }

    private function kiemTraYeuCau(string $ma): void
    {
        $cauHinh = $this->quanLyMoDun->docCauHinhTheoMa($ma);
        $php = (string)($cauHinh['yeu_cau_php'] ?? '');
        $core = (string)($cauHinh['yeu_cau_core'] ?? '');
        if ($php !== '' && version_compare(PHP_VERSION, $php, '<')) {
            throw new \RuntimeException('Yeu cau PHP ' . $php . ', hien tai ' . PHP_VERSION . '.');
        }
        if ($core !== '' && version_compare(self::PHIEN_BAN_CORE, $core, '<')) {
            throw new \RuntimeException('Yeu cau core ' . $core . ', hien tai ' . self::PHIEN_BAN_CORE . '.');
        }
    }

    private function kiemTraPhuThuocBatBuoc(string $ma): void
    {
        $thieu = $this->quanLyMoDun->kiemTraPhuThuoc($ma);
        if ($thieu !== []) {
            throw new \RuntimeException('Chua kich hoat phu thuoc: ' . implode(', ', $thieu));
        }
    }

    private function chayTepSqlNeuCo(string $duongDan): void
    {
        if (!is_file($duongDan)) {
            return;
        }
        $noiDung = (string)file_get_contents($duongDan);
        $pdo = CoSoDuLieu::layKetNoi();
        foreach (array_filter(array_map('trim', explode(';', $noiDung))) as $sql) {
            if ($sql !== '') {
                $pdo->exec($sql);
            }
        }
    }

    private function duongDanSqlGoCaiDat(string $thuMuc): ?string
    {
        $goc = realpath($thuMuc);
        $duongDan = realpath($thuMuc . '/co_so_du_lieu/uninstall.sql');
        if ($goc === false || $duongDan === false) {
            return null;
        }
        if (!str_starts_with($duongDan, $goc . DIRECTORY_SEPARATOR)) {
            throw new \RuntimeException('Tep SQL go cai dat khong hop le.');
        }
        return $duongDan;
    }

    private function chaySqlFile(?string $duongDanFile): void
    {
        if ($duongDanFile === null || !is_file($duongDanFile)) {
            return;
        }

        $noiDung = (string)file_get_contents($duongDanFile);
        if (trim($noiDung) === '') {
            return;
        }

        $pdo = CoSoDuLieu::layKetNoi();
        foreach (array_filter(array_map('trim', explode(';', $noiDung))) as $sql) {
            if ($sql === '') {
                continue;
            }
            $pdo->exec($sql);
        }
    }

    private function chayFileVongDoi(string $thuMuc, string $tep, string $ma): void
    {
        $duongDan = realpath($thuMuc . '/' . $tep);
        $goc = realpath($thuMuc);
        if ($duongDan === false || $goc === false || !str_starts_with($duongDan, $goc . DIRECTORY_SEPARATOR)) {
            return;
        }
        if (!is_file($duongDan)) {
            return;
        }
        $thucThi = require $duongDan;
        if (is_callable($thucThi)) {
            $thucThi($ma, CoSoDuLieu::layKetNoi());
        }
    }

    private function dongBoQuyen(string $ma): void
    {
        $cauHinh = $this->quanLyMoDun->docCauHinhTheoMa($ma);
        $quyen = $cauHinh['quyen'] ?? [];
        $maQuyen = [];
        if (array_is_list($quyen)) {
            foreach ($quyen as $q) {
                if (is_string($q) && $q !== '') {
                    $maQuyen[] = $q;
                }
            }
        } else {
            foreach ($quyen as $q => $moTa) {
                if (is_string($q) && $q !== '') {
                    $maQuyen[] = $q;
                }
            }
        }
        if ($maQuyen === []) {
            return;
        }
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES (1, :ma_quyen) ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen)');
        foreach (array_unique($maQuyen) as $q) {
            $stm->execute(['ma_quyen' => $q]);
        }
    }

    private function dongBoMenu(string $ma): void
    {
        $duLieuNap = $this->quanLyMoDun->duLieuNapHeThong();
        $this->quanLyMenu->dongBoMenuTuMoDun($duLieuNap['tat_ca_mo_dun']);
    }

    private function xoaDuLieuHeThongMoDun(string $ma): void
    {
        $maLike = $ma . '.%';

        if ($this->bangTonTai('menu_he_thong') && $this->coCot('menu_he_thong', 'mo_dun_ma')) {
            $this->xoaNeuBangTonTai(
                'menu_he_thong',
                'DELETE FROM menu_he_thong WHERE mo_dun_ma = :ma',
                ['ma' => $ma]
            );
        } else {
            $this->ghiLogXoaDuLieu('menu_he_thong', 0, true);
        }

        if ($this->bangTonTai('quyen_vai_tro')) {
            if ($this->coCot('quyen_vai_tro', 'quyen_ma')) {
                $this->xoaNeuBangTonTai(
                    'quyen_vai_tro',
                    'DELETE FROM quyen_vai_tro WHERE quyen_ma LIKE :ma_like',
                    ['ma_like' => $maLike]
                );
            } elseif ($this->coCot('quyen_vai_tro', 'ma_quyen')) {
                $this->xoaNeuBangTonTai(
                    'quyen_vai_tro',
                    'DELETE FROM quyen_vai_tro WHERE ma_quyen LIKE :ma_like',
                    ['ma_like' => $maLike]
                );
            }
        } else {
            $this->ghiLogXoaDuLieu('quyen_vai_tro', 0, true);
        }

        if ($this->bangTonTai('quyen')) {
            if ($this->coCot('quyen', 'mo_dun_ma') && $this->coCot('quyen', 'ma')) {
                $this->xoaNeuBangTonTai(
                    'quyen',
                    'DELETE FROM quyen WHERE mo_dun_ma = :ma OR ma LIKE :ma_like',
                    ['ma' => $ma, 'ma_like' => $maLike]
                );
            } elseif ($this->coCot('quyen', 'ma')) {
                $this->xoaNeuBangTonTai(
                    'quyen',
                    'DELETE FROM quyen WHERE ma LIKE :ma_like',
                    ['ma_like' => $maLike]
                );
            } else {
                $this->ghiLogXoaDuLieu('quyen', 0, true);
            }
        } else {
            $this->ghiLogXoaDuLieu('quyen', 0, true);
        }

        foreach (['cau_hinh_mo_dun', 'tai_nguyen_mo_dun', 'lich_su_migration'] as $bang) {
            if ($this->bangTonTai($bang) && $this->coCot($bang, 'mo_dun_ma')) {
                $this->xoaNeuBangTonTai(
                    $bang,
                    "DELETE FROM {$bang} WHERE mo_dun_ma = :ma",
                    ['ma' => $ma]
                );
                continue;
            }

            $this->ghiLogXoaDuLieu($bang, 0, true);
        }

        if ($this->bangTonTai('giao_dien') && $this->coCot('giao_dien', 'mo_dun_ma')) {
            $this->xoaNeuBangTonTai(
                'giao_dien',
                'DELETE FROM giao_dien WHERE mo_dun_ma = :ma',
                ['ma' => $ma]
            );
        }
    }

    private function xoaBanGhiMoDun(string $ma): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('DELETE FROM mo_dun WHERE ma = :ma');
        $stm->execute(['ma' => $ma]);
    }

    private function ghiNhatKyGoCaiDat(string $ma, bool $daXoaThuMuc, ?string $loiXoaThuMuc = null): void
    {
        try {
            $pdo = CoSoDuLieu::layKetNoi();
            $noiDung = $daXoaThuMuc
                ? 'Go cai dat mo dun, xoa du lieu CSDL va xoa thu muc ma nguon.'
                : 'Go cai dat mo dun khoi CSDL thanh cong nhung khong the xoa thu muc ma nguon: ' . (string)$loiXoaThuMuc;
            foreach (['nhat_ky_he_thong', 'nhat_ky', 'he_thong_log', 'audit_log'] as $bang) {
                if (!$this->bangTonTai($bang)) {
                    continue;
                }

                if ($this->coCot($bang, 'hanh_dong') && $this->coCot($bang, 'doi_tuong') && $this->coCot($bang, 'noi_dung')) {
                    $stm = $pdo->prepare("INSERT INTO {$bang} (hanh_dong, doi_tuong, noi_dung) VALUES (:hanh_dong, :doi_tuong, :noi_dung)");
                    $stm->execute([
                        'hanh_dong' => 'go_cai_dat_mo_dun',
                        'doi_tuong' => $ma,
                        'noi_dung' => $noiDung,
                    ]);
                    return;
                }
            }
        } catch (Throwable) {
        }

        if ($daXoaThuMuc) {
            error_log('[module_uninstall] ' . $ma . ' da duoc go cai dat va xoa khoi he thong.');
            return;
        }

        error_log('[module_uninstall] ' . $ma . ' da duoc go cai dat khoi CSDL nhung khong the xoa thu muc ma nguon: ' . (string)$loiXoaThuMuc);
    }

    private function capNhatTrangThaiMenu(string $ma, int $trangThai): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('UPDATE menu_he_thong SET trang_thai = :trang_thai, ngay_cap_nhat = NOW() WHERE mo_dun_ma = :ma');
        $stm->execute(['trang_thai' => $trangThai, 'ma' => $ma]);
    }

    private function capNhatTrangThai(string $ma, string $trangThai, array $cotMoc): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $set = ["trang_thai = :trang_thai", "loi = NULL", "ngay_cap_nhat = NOW()"];
        foreach ($cotMoc as $cot => $giaTri) {
            $set[] = $cot . ' = ' . $giaTri;
        }
        $sql = 'UPDATE mo_dun SET ' . implode(', ', $set) . ' WHERE ma = :ma';
        $stm = $pdo->prepare($sql);
        $stm->execute(['trang_thai' => $trangThai, 'ma' => $ma]);
    }

    private function thucThiAnToan(string $hookTruoc, string $hookSau, callable $task, string $ma): array
    {
        try {
            SuKien::goiHanhDong($hookTruoc, $ma);
            $ketQua = $task();
            SuKien::goiHanhDong($hookSau, $ma, $ketQua);
            return $ketQua;
        } catch (Throwable $e) {
            $pdo = CoSoDuLieu::layKetNoi();
            $stm = $pdo->prepare("UPDATE mo_dun SET trang_thai = 'loi', loi = :loi, ngay_cap_nhat = NOW() WHERE ma = :ma");
            $stm->execute(['loi' => $e->getMessage(), 'ma' => $ma]);
            return ['ok' => false, 'thong_bao' => $e->getMessage()];
        }
    }

    private function laMoDunGiaoDien(string $ma): bool
    {
        $cauHinh = $this->quanLyMoDun->docCauHinhTheoMa($ma);
        return (string)($cauHinh['loai'] ?? '') === 'giao_dien';
    }

    private function bangTonTai(string $tenBang): bool
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :bang');
        $stm->execute(['bang' => $tenBang]);
        return (int)$stm->fetchColumn() > 0;
    }

    private function xoaNeuBangTonTai(string $tenBang, string $sql, array $params): void
    {
        if (!$this->bangTonTai($tenBang)) {
            $this->ghiLogXoaDuLieu($tenBang, 0, true);
            return;
        }

        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare($sql);
        $stm->execute($params);
        $this->ghiLogXoaDuLieu($tenBang, $stm->rowCount(), false);
    }

    private function coCot(string $bang, string $cot): bool
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :bang AND COLUMN_NAME = :cot');
        $stm->execute(['bang' => $bang, 'cot' => $cot]);
        return (int)$stm->fetchColumn() > 0;
    }

    private function ghiLogXoaDuLieu(string $bang, int $soDong, bool $boQua): void
    {
        $trangThai = $boQua ? 'bo_qua' : 'da_xoa';
        error_log(sprintf('[module_uninstall] bang=%s trang_thai=%s so_dong=%d', $bang, $trangThai, $soDong));
    }

    private function xoaThuMucBangPhp(string $duongDan): void
    {
        $gocUngDung = defined('GOC_UNG_DUNG') ? GOC_UNG_DUNG : (GOC_DU_AN . '/ung_dung');
        $gocMoDun = realpath($gocUngDung . '/mo_dun');
        if ($gocMoDun === false) {
            throw new \RuntimeException('Khong tim thay thu muc goc cua mo dun.');
        }

        $duongDanThat = realpath($duongDan);
        if ($duongDanThat === false || !str_starts_with($duongDanThat, $gocMoDun . DIRECTORY_SEPARATOR)) {
            throw new \RuntimeException('Duong dan mo dun khong hop le');
        }

        if (!is_dir($duongDanThat)) {
            throw new \RuntimeException('Khong tim thay thu muc mo dun de xoa.');
        }

        if ($duongDanThat === $gocMoDun) {
            throw new \RuntimeException('Duong dan mo dun khong hop le.');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($duongDanThat, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $muc) {
            $duongDanMuc = $muc->getPathname();
            if ($muc->isLink()) {
                if (!@unlink($duongDanMuc)) {
                    throw new \RuntimeException('Khong the xoa lien ket: ' . $duongDanMuc);
                }
                continue;
            }

            if ($muc->isDir()) {
                if (!@rmdir($duongDanMuc)) {
                    throw new \RuntimeException('Khong the xoa thu muc con: ' . $duongDanMuc);
                }
                continue;
            }

            if (!@unlink($duongDanMuc)) {
                throw new \RuntimeException('Khong the xoa tep: ' . $duongDanMuc);
            }
        }

        if (!@rmdir($duongDanThat)) {
            throw new \RuntimeException('Khong the xoa thu muc mo dun goc.');
        }
    }

    private function tatGiaoDienKhac(string $maDangBat): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        foreach ($this->quanLyMoDun->danhSachTuCSDL() as $m) {
            if (($m['loai'] ?? '') !== 'giao_dien' || $m['ma'] === $maDangBat) {
                continue;
            }
            $stm = $pdo->prepare("UPDATE mo_dun SET trang_thai = 'dang_tat', ngay_tat = NOW(), ngay_cap_nhat = NOW() WHERE ma = :ma");
            $stm->execute(['ma' => $m['ma']]);
            $this->capNhatTrangThaiMenu($m['ma'], 0);
        }
    }
}
