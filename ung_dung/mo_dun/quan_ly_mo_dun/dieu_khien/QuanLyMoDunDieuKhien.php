<?php
declare(strict_types=1);

namespace MoDun\QuanLyMoDun\DieuKhien;

use HeThong\TaiLenMoDun;
use HeThong\VongDoiMoDun;
use HeThong\YeuCau;
use MoDun\QuanLyMoDun\MoHinh\QuanLyMoDun;

class QuanLyMoDunDieuKhien
{
    public static function danhSach(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $moHinh = new QuanLyMoDun();
        $GLOBALS['tieu_de_trang'] = 'Quan ly mo dun';
        hien_thi_bo_cuc(
            GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/danh_sach.php',
            ['danhSach' => $moHinh->danhSach()]
        );
    }

    public static function kiemTra(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }
        $moHinh = new QuanLyMoDun();
        $ketQua = $moHinh->kiemTra($ma);
        $GLOBALS['tieu_de_trang'] = 'Kiem tra mo dun';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/kiem_tra.php', [
            'ketQua' => $ketQua,
            'maMoDun' => $ma,
        ]);
    }

    public static function chiTiet(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }
        $moHinh = new QuanLyMoDun();
        $chiTiet = $moHinh->chiTiet($ma);
        if ($chiTiet === null) {
            self::baoLoi('Khong tim thay mo dun.');
            return;
        }
        $GLOBALS['tieu_de_trang'] = 'Chi tiet mo dun';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/chi_tiet.php', [
            'moDun' => $chiTiet,
        ]);
    }

    public static function chiTietLegacy(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }
        chuyen_huong('/quan-ly-mo-dun/kiem-tra/' . $ma);
    }

    public static function caiDat(YeuCau $yeuCau, array $thamSo): void
    {
        self::xuLyHanhDong($yeuCau, $thamSo, 'caiDat');
    }

    public static function hienThiTaiLen(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        if (!co_quyen('quan_ly_mo_dun.tai_len')) {
            self::baoLoi('Ban khong co quyen tai mo dun.');
            return;
        }

        $GLOBALS['tieu_de_trang'] = 'Tai mo dun';
        hien_thi_bo_cuc(
            GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/tai_len.php'
        );
    }

    public static function xuLyTaiLen(YeuCau $yeuCau, array $thamSo): void
    {
        if (!co_quyen('quan_ly_mo_dun.tai_len')) {
            self::baoLoi('Ban khong co quyen tai mo dun.');
            return;
        }

        if (!csrf_kiem_tra((string)$yeuCau->dauVao('_csrf'))) {
            self::baoLoi('CSRF token khong hop le.');
            return;
        }

        $xacNhan = (string)$yeuCau->dauVao('xac_nhan_nguon_tin_cay', '') === '1';
        if (!$xacNhan) {
            $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => 'Can xac nhan chi tai mo dun tu nguon tin cay.'];
            chuyen_huong('/quan-ly-mo-dun/tai-len');
        }

        try {
            $tepTaiLen = $_FILES['tep_zip'] ?? [];
            $dichVu = new TaiLenMoDun();
            $dichVu->xuLy(is_array($tepTaiLen) ? $tepTaiLen : []);
            $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Tai mo dun thanh cong. Vui long cai dat de su dung.'];
            chuyen_huong('/quan-ly-mo-dun');
        } catch (\Throwable $e) {
            $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => $e->getMessage()];
            chuyen_huong('/quan-ly-mo-dun/tai-len');
        }
    }

    public static function kichHoat(YeuCau $yeuCau, array $thamSo): void
    {
        self::xuLyHanhDong($yeuCau, $thamSo, 'kichHoat');
    }

    public static function tat(YeuCau $yeuCau, array $thamSo): void
    {
        self::xuLyHanhDong($yeuCau, $thamSo, 'tat');
    }

    public static function goCaiDat(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }
        $xacNhan = (string)$yeuCau->dauVao('xac_nhan_xoa_du_lieu', '') === '1';
        if (!$xacNhan) {
            $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => 'Can xac nhan viec xoa toan bo du lieu va ma nguon mo dun truoc khi go cai dat.'];
            chuyen_huong('/quan-ly-mo-dun');
        }
        $vongDoiMoDun = new VongDoiMoDun();
        self::ganThongBao($vongDoiMoDun->goCaiDat($ma));
        chuyen_huong('/quan-ly-mo-dun');
    }

    private static function xuLyHanhDong(YeuCau $yeuCau, array $thamSo, string $ham): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }
        $moHinh = new QuanLyMoDun();
        self::ganThongBao($moHinh->{$ham}($ma));
        chuyen_huong('/quan-ly-mo-dun');
    }

    public static function batTatLegacy(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = self::maHopLe((string)($thamSo['ma'] ?? ''));
        if ($ma === null) {
            self::baoLoi('Ma mo dun khong hop le.');
            return;
        }

        $trangThaiMoi = (string)$yeuCau->dauVao('trang_thai', '') === '1';
        $moHinh = new QuanLyMoDun();
        $ketQua = $trangThaiMoi ? $moHinh->kichHoat($ma) : $moHinh->tat($ma);
        self::ganThongBao($ketQua);
        chuyen_huong('/quan-ly-mo-dun');
    }

    private static function ganThongBao(array $ketQua): void
    {
        $_SESSION['_thong_bao'] = [
            'loai' => !empty($ketQua['ok']) ? 'success' : 'danger',
            'noi_dung' => (string)($ketQua['thong_bao'] ?? 'Khong the thuc hien thao tac.'),
        ];
    }

    private static function maHopLe(string $ma): ?string
    {
        return preg_match('/^[a-z0-9_]+$/', $ma) ? $ma : null;
    }

    private static function baoLoi(string $thongBao): void
    {
        $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => $thongBao];
        chuyen_huong('/quan-ly-mo-dun');
    }
}
