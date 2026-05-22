<?php
declare(strict_types=1);

namespace MoDun\XacThuc\DieuKhien;

use HeThong\XacThuc;
use HeThong\YeuCau;

class XacThucDieuKhien
{
    private static function duongDanViewDangNhap(): string
    {
        return GOC_DU_AN . '/ung_dung/mo_dun/xac_thuc/giao_dien/dang_nhap.php';
    }

    public static function formDangNhap(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        if ((new XacThuc())->daDangNhap()) chuyen_huong('/');
        hien_thi_bo_cuc(self::duongDanViewDangNhap(), ['tieuDe' => 'Dang nhap'], 'dang_nhap');
    }

    public static function xuLyDangNhap(YeuCau $yeuCau): void
    {
        $ok = (new XacThuc())->dangNhap((string)$yeuCau->dauVao('email'), (string)$yeuCau->dauVao('mat_khau'));
        if ($ok) chuyen_huong('/');
        $loi = 'Thong tin dang nhap khong dung.';
        hien_thi_bo_cuc(self::duongDanViewDangNhap(), ['tieuDe' => 'Dang nhap', 'loi' => $loi], 'dang_nhap');
    }

    public static function dangXuat(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        (new XacThuc())->dangXuat();
        chuyen_huong('/dang-nhap');
    }
}
