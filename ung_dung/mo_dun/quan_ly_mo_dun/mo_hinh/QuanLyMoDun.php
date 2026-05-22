<?php
declare(strict_types=1);

namespace MoDun\QuanLyMoDun\MoHinh;

use HeThong\QuanLyMoDun as QuanLyMoDunLoi;
use HeThong\VongDoiMoDun;

class QuanLyMoDun
{
    private QuanLyMoDunLoi $quanLy;
    private VongDoiMoDun $vongDoi;

    public function __construct()
    {
        $this->quanLy = new QuanLyMoDunLoi();
        $this->vongDoi = new VongDoiMoDun();
    }

    public function danhSach(): array
    {
        return $this->quanLy->danhSachTuCSDL();
    }

    public function chiTiet(string $ma): ?array
    {
        return $this->quanLy->thongTinMoDun($ma);
    }

    public function caiDat(string $ma): array
    {
        return $this->vongDoi->caiDat($ma);
    }

    public function kichHoat(string $ma): array
    {
        return $this->vongDoi->kichHoat($ma);
    }

    public function tat(string $ma): array
    {
        return $this->vongDoi->tat($ma);
    }

    public function goCaiDat(string $ma): array
    {
        return $this->vongDoi->goCaiDat($ma);
    }

    public function kiemTra(string $ma): array
    {
        return $this->vongDoi->kiemTra($ma);
    }
}
