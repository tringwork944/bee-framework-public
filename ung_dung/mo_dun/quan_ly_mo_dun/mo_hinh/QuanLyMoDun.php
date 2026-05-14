<?php
declare(strict_types=1);

namespace MoDun\QuanLyMoDun\MoHinh;

class QuanLyMoDun
{
    private const MO_DUN_LOI = ['xac_thuc', 'tong_quan', 'tai_khoan', 'quan_ly_mo_dun'];

    public function danhSach(): array
    {
        $ketQua = [];
        $trangThaiNgoai = $this->docTrangThai();
        $thuMucMoDun = GOC_DU_AN . '/ung_dung/mo_dun';
        $thuMucCon = glob($thuMucMoDun . '/*', GLOB_ONLYDIR) ?: [];

        foreach ($thuMucCon as $thuMuc) {
            $maThuMuc = basename($thuMuc);
            if (!$this->maHopLe($maThuMuc)) {
                continue;
            }

            $thongTin = $this->docThongTinCoBan($maThuMuc, $thuMuc);
            $cauTruc = $this->kiemTraCauTruc($maThuMuc);
            $thongTin['loi_cau_truc'] = $cauTruc['loi'];
            $thongTin['hop_le'] = empty($cauTruc['loi']);

            $macDinh = (bool)($thongTin['kich_hoat_mac_dinh'] ?? false);
            $thongTin['kich_hoat'] = $this->xacDinhTrangThai($maThuMuc, $macDinh, $trangThaiNgoai);
            $thongTin['la_mo_dun_loi'] = in_array($maThuMuc, self::MO_DUN_LOI, true);
            $ketQua[] = $thongTin;
        }

        usort($ketQua, static fn($a, $b) => strcmp((string)$a['ma'], (string)$b['ma']));
        return $ketQua;
    }

    public function chiTiet(string $ma): ?array
    {
        if (!$this->maHopLe($ma)) return null;
        foreach ($this->danhSach() as $m) {
            if ($m['ma'] === $ma) return $m;
        }
        return null;
    }

    public function kiemTraCauTruc(string $ma): array
    {
        if (!$this->maHopLe($ma)) {
            return ['loi' => ['Ma mo dun khong hop le.']];
        }

        $thuMuc = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
        $loi = [];
        if (!is_dir($thuMuc)) return ['loi' => ['Khong ton tai thu muc mo dun.']];
        if (!is_file($thuMuc . '/cau_hinh.php')) $loi[] = 'Thieu cau_hinh.php';
        if (!is_dir($thuMuc . '/dieu_khien') && !is_dir($thuMuc . '/DieuKhien')) $loi[] = 'Thieu thu muc dieu_khien';
        if (!is_dir($thuMuc . '/giao_dien') && !is_dir($thuMuc . '/GiaoDien')) $loi[] = 'Thieu thu muc giao_dien';

        $cauHinh = $this->taiCauHinh($thuMuc . '/cau_hinh.php');
        if ($cauHinh === null) {
            $loi[] = 'Khong doc duoc cau_hinh.php';
        } else {
            if (!isset($cauHinh['ma']) || !is_string($cauHinh['ma']) || $cauHinh['ma'] === '') $loi[] = 'Thieu truong ma';
            if (!isset($cauHinh['ten']) || !is_string($cauHinh['ten']) || $cauHinh['ten'] === '') $loi[] = 'Thieu truong ten';
            if (!array_key_exists('kich_hoat', $cauHinh)) $loi[] = 'Thieu truong kich_hoat';
        }

        return ['loi' => $loi];
    }

    public function batTat(string $ma, bool $trangThai): array
    {
        if (!$this->maHopLe($ma)) {
            return ['ok' => false, 'thong_bao' => 'Ma mo dun khong hop le.'];
        }
        if (in_array($ma, self::MO_DUN_LOI, true)) {
            return ['ok' => false, 'thong_bao' => 'Khong the tat/bat mo dun loi.'];
        }
        $chiTiet = $this->chiTiet($ma);
        if ($chiTiet === null) {
            return ['ok' => false, 'thong_bao' => 'Khong tim thay mo dun.'];
        }

        $duLieu = $this->docTrangThai();
        $duLieu[$ma] = $trangThai;
        $ok = $this->ghiTrangThai($duLieu);
        if (!$ok) {
            return ['ok' => false, 'thong_bao' => 'Khong ghi duoc file trang thai mo dun.'];
        }
        return ['ok' => true, 'thong_bao' => $trangThai ? 'Da bat mo dun.' : 'Da tat mo dun.'];
    }

    private function docThongTinCoBan(string $maThuMuc, string $thuMuc): array
    {
        $cauHinh = $this->taiCauHinh($thuMuc . '/cau_hinh.php') ?? [];
        $route = is_array($cauHinh['route'] ?? null) ? $cauHinh['route'] : [];
        $menu = $cauHinh['menu'] ?? null;
        $quyen = is_array($cauHinh['quyen'] ?? null) ? $cauHinh['quyen'] : [];

        return [
            'ma' => $maThuMuc,
            'ten' => (string)($cauHinh['ten'] ?? $maThuMuc),
            'phien_ban' => (string)($cauHinh['phien_ban'] ?? ''),
            'tac_gia' => (string)($cauHinh['tac_gia'] ?? ''),
            'mo_ta' => (string)($cauHinh['mo_ta'] ?? ''),
            'kich_hoat_mac_dinh' => (bool)($cauHinh['kich_hoat'] ?? false),
            'anh_huong_menu' => !empty($cauHinh['anh_huong_menu']),
            'so_route' => count($route),
            'so_menu' => $this->demMenu($menu),
            'so_quyen' => count($quyen),
            'duong_dan' => $thuMuc,
        ];
    }

    private function demMenu(mixed $menu): int
    {
        if (!is_array($menu)) return 0;
        $tong = 1;
        foreach (($menu['con'] ?? []) as $con) {
            $tong += $this->demMenu($con);
        }
        return $tong;
    }

    private function taiCauHinh(string $duongDan): ?array
    {
        if (!is_file($duongDan)) return null;
        try {
            $cauHinh = require $duongDan;
        } catch (\Throwable) {
            return null;
        }
        return is_array($cauHinh) ? $cauHinh : null;
    }

    private function maHopLe(string $ma): bool
    {
        return (bool)preg_match('/^[a-z0-9_]+$/', $ma);
    }

    private function docTrangThai(): array
    {
        $duongDan = GOC_DU_AN . '/ung_dung/kho_luu/mo_dun/trang_thai.json';
        if (!is_file($duongDan)) return [];
        $noiDung = file_get_contents($duongDan);
        if (!is_string($noiDung) || trim($noiDung) === '') return [];
        $duLieu = json_decode($noiDung, true);
        return is_array($duLieu) ? $duLieu : [];
    }

    private function ghiTrangThai(array $duLieu): bool
    {
        $thuMuc = GOC_DU_AN . '/ung_dung/kho_luu/mo_dun';
        if (!is_dir($thuMuc) && !mkdir($thuMuc, 0775, true) && !is_dir($thuMuc)) {
            return false;
        }

        $loc = [];
        foreach ($duLieu as $ma => $tt) {
            if (!is_string($ma) || !$this->maHopLe($ma)) continue;
            if (in_array($ma, self::MO_DUN_LOI, true)) continue;
            $loc[$ma] = (bool)$tt;
        }

        $json = json_encode($loc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (!is_string($json)) return false;
        return file_put_contents($thuMuc . '/trang_thai.json', $json) !== false;
    }

    private function xacDinhTrangThai(string $ma, bool $macDinh, array $ngoai): bool
    {
        if (in_array($ma, self::MO_DUN_LOI, true)) return true;
        if (array_key_exists($ma, $ngoai)) return (bool)$ngoai[$ma];
        return $macDinh;
    }
}
