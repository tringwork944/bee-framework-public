<?php
declare(strict_types=1);

namespace HeThong;

class QuanLyTaiNguyen
{
    public function taoTaiNguyen(array $moDun): array
    {
        $khaiBao = $moDun['tai_nguyen'] ?? [];
        $css = $this->chuanHoaDanhSach($khaiBao['css'] ?? []);
        $js = $this->chuanHoaDanhSach($khaiBao['js'] ?? []);
        return ['css' => $css, 'js' => $js];
    }

    private function chuanHoaDanhSach(mixed $ds): array
    {
        if (!is_array($ds)) return [];
        $ketQua = [];
        foreach ($ds as $item) {
            if (!is_string($item) || trim($item) === '') continue;
            $duongDan = trim($item);
            if (!str_starts_with($duongDan, '/')) {
                $duongDan = '/' . $duongDan;
            }
            $ketQua[] = $duongDan;
        }
        return array_values(array_unique($ketQua));
    }
}
