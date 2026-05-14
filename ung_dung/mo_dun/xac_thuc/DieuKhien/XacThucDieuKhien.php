<?php
declare(strict_types=1);

namespace MoDun\XacThuc\DieuKhien;

use HeThong\XacThuc;
use HeThong\YeuCau;

class XacThucDieuKhien
{
    public static function formDangNhap(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        if ((new XacThuc())->daDangNhap()) chuyen_huong('/tong-quan');
        $GLOBALS['tieu_de_trang'] = 'Dang nhap';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/giao_dien/trang/xac_thuc/dang_nhap.php');
    }

    public static function xuLyDangNhap(YeuCau $yeuCau): void
    {
        $ok = (new XacThuc())->dangNhap((string)$yeuCau->dauVao('email'), (string)$yeuCau->dauVao('mat_khau'));
        if ($ok) chuyen_huong('/tong-quan');
        $loi = 'Thong tin dang nhap khong dung.';
        $GLOBALS['tieu_de_trang'] = 'Dang nhap';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/giao_dien/trang/xac_thuc/dang_nhap.php', ['loi' => $loi]);
    }

    public static function dangXuat(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        (new XacThuc())->dangXuat();
        chuyen_huong('/dang-nhap');
    }
}
