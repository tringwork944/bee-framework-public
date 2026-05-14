<?php
declare(strict_types=1);

namespace HeThong;

class QuanLyGiaoDien
{
    public function __construct(
        private readonly QuanLyMenu $quanLyMenu,
        private readonly QuanLyTaiNguyen $quanLyTaiNguyen
    ) {
    }

    public function chuanBi(array $duLieuMoDun, array $tuyen, ?array $nguoiDung, string $duongDanHienTai): void
    {
        $moDun = is_array($tuyen['_mo_dun'] ?? null) ? $tuyen['_mo_dun'] : [];
        $giaoDien = is_array($moDun['giao_dien'] ?? null) ? $moDun['giao_dien'] : [];

        $breadcrumb = $this->taoBreadcrumb($giaoDien, $duongDanHienTai);
        $tieuDe = (string)($giaoDien['tieu_de'] ?? $moDun['ten'] ?? 'Bee Framework');

        $GLOBALS['menu_he_thong'] = $this->quanLyMenu->layMenuChoGiaoDien($nguoiDung, $duongDanHienTai);
        $GLOBALS['thong_tin_mo_dun'] = $moDun;
        $GLOBALS['tai_nguyen_mo_dun'] = $this->quanLyTaiNguyen->taoTaiNguyen($moDun);
        $GLOBALS['breadcrumb'] = $breadcrumb;
        $GLOBALS['tieu_de_trang'] = $tieuDe;
        $GLOBALS['bo_cuc_mac_dinh'] = (string)($giaoDien['layout'] ?? 'chinh');
    }

    private function taoBreadcrumb(array $giaoDien, string $duongDanHienTai): array
    {
        $khaiBao = $giaoDien['breadcrumb'] ?? null;
        if (is_array($khaiBao) && $khaiBao !== []) {
            $ketQua = [];
            foreach ($khaiBao as $item) {
                if (!is_array($item)) continue;
                $tieuDe = (string)($item['tieu_de'] ?? '');
                if ($tieuDe === '') continue;
                $ketQua[] = [
                    'tieu_de' => $tieuDe,
                    'duong_dan' => isset($item['duong_dan']) ? (string)$item['duong_dan'] : null,
                ];
            }
            if ($ketQua !== []) return $ketQua;
        }

        $parts = array_values(array_filter(explode('/', trim($duongDanHienTai, '/'))));
        $auto = [['tieu_de' => 'Tong quan', 'duong_dan' => '/tong-quan']];
        $currentPath = '';
        foreach ($parts as $part) {
            $currentPath .= '/' . $part;
            $auto[] = [
                'tieu_de' => ucwords(str_replace('-', ' ', $part)),
                'duong_dan' => $currentPath,
            ];
        }
        return $auto;
    }
}
