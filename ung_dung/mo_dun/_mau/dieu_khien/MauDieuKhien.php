<?php
declare(strict_types=1);

namespace MoDun\Mau\DieuKhien;

use HeThong\YeuCau;
use MoDun\Mau\MoHinh\Mau;

class MauDieuKhien
{
    public static function danhSach(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $duLieu = (new Mau())->layDanhSach();
        $GLOBALS['tieu_de_trang'] = 'Mo dun mau - Danh sach';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/_mau/giao_dien/danh_sach.php', ['duLieu' => $duLieu]);
    }

    public static function them(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $GLOBALS['tieu_de_trang'] = 'Mo dun mau - Them';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/_mau/giao_dien/them.php');
    }

    public static function luu(YeuCau $yeuCau, array $thamSo = []): void
    {
        // Vi du:
        // (new Mau())->tao([
        //     'ten' => (string)$yeuCau->dauVao('ten'),
        // ]);
        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da luu du lieu mau.'];
        chuyen_huong('/mau');
    }

    public static function sua(YeuCau $yeuCau, array $thamSo = []): void
    {
        $id = (int)($thamSo['id'] ?? 0);
        $banGhi = (new Mau())->timTheoId($id);
        if (!$banGhi) {
            http_response_code(404);
            echo 'Khong tim thay du lieu.';
            return;
        }

        $GLOBALS['tieu_de_trang'] = 'Mo dun mau - Sua';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/_mau/giao_dien/sua.php', ['banGhi' => $banGhi]);
    }

    public static function capNhat(YeuCau $yeuCau, array $thamSo = []): void
    {
        $id = (int)($thamSo['id'] ?? 0);
        // Vi du:
        // (new Mau())->capNhat($id, [
        //     'ten' => (string)$yeuCau->dauVao('ten'),
        // ]);
        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da cap nhat du lieu mau.'];
        chuyen_huong('/mau');
    }

    public static function xoa(YeuCau $yeuCau, array $thamSo = []): void
    {
        $id = (int)($thamSo['id'] ?? 0);
        // Vi du: (new Mau())->xoa($id);
        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da xoa du lieu mau.'];
        chuyen_huong('/mau');
    }
}
