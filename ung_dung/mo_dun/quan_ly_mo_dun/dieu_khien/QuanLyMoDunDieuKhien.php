<?php
declare(strict_types=1);

namespace MoDun\QuanLyMoDun\DieuKhien;

use HeThong\YeuCau;
use MoDun\QuanLyMoDun\MoHinh\QuanLyMoDun;

class QuanLyMoDunDieuKhien
{
    public static function danhSach(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $moHinh = new QuanLyMoDun();
        $ds = $moHinh->danhSach();
        $GLOBALS['tieu_de_trang'] = 'Quan ly mo dun';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/danh_sach.php', ['danhSach' => $ds]);
    }

    public static function chiTiet(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = (string)($thamSo['ma'] ?? '');
        if (!preg_match('/^[a-z0-9_]+$/', $ma)) {
            http_response_code(400);
            echo 'Ma mo dun khong hop le.';
            return;
        }
        $moHinh = new QuanLyMoDun();
        $moDun = $moHinh->chiTiet($ma);
        if ($moDun === null) {
            http_response_code(404);
            echo 'Khong tim thay mo dun.';
            return;
        }
        $GLOBALS['tieu_de_trang'] = 'Chi tiet mo dun';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/chi_tiet.php', ['moDun' => $moDun]);
    }

    public static function kiemTra(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = (string)($thamSo['ma'] ?? '');
        if (!preg_match('/^[a-z0-9_]+$/', $ma)) {
            http_response_code(400);
            echo 'Ma mo dun khong hop le.';
            return;
        }
        $moHinh = new QuanLyMoDun();
        $ketQua = $moHinh->kiemTraCauTruc($ma);
        $moDun = $moHinh->chiTiet($ma);
        $GLOBALS['tieu_de_trang'] = 'Kiem tra mo dun';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/quan_ly_mo_dun/giao_dien/kiem_tra.php', [
            'moDun' => $moDun,
            'ketQua' => $ketQua,
            'maMoDun' => $ma,
        ]);
    }

    public static function batTat(YeuCau $yeuCau, array $thamSo): void
    {
        $ma = (string)($thamSo['ma'] ?? '');
        if (!preg_match('/^[a-z0-9_]+$/', $ma)) {
            $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => 'Ma mo dun khong hop le.'];
            chuyen_huong('/quan-ly-mo-dun');
        }

        $trangThaiMoi = (string)$yeuCau->dauVao('trang_thai', '') === '1';
        $moHinh = new QuanLyMoDun();
        $ketQua = $moHinh->batTat($ma, $trangThaiMoi);
        $_SESSION['_thong_bao'] = [
            'loai' => $ketQua['ok'] ? 'success' : 'danger',
            'noi_dung' => $ketQua['thong_bao'],
        ];
        chuyen_huong('/quan-ly-mo-dun');
    }
}
