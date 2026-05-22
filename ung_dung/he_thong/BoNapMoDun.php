<?php
declare(strict_types=1);

namespace HeThong;

class BoNapMoDun
{
    public function nap(): array
    {
        $quanLy = new QuanLyMoDun();
        return $quanLy->duLieuNapHeThong();
    }
}
