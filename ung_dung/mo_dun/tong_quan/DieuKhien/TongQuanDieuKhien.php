<?php
declare(strict_types=1);

namespace MoDun\TongQuan\DieuKhien;

class TongQuanDieuKhien
{
    public static function index(?\HeThong\YeuCau $yeuCau = null, array $thamSo = []): void
    {
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tong_quan/GiaoDien/index.php');
    }
}
