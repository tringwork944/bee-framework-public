<?php
declare(strict_types=1);

namespace HeThong;

class BoNapMoDun
{
    private const MO_DUN_LOI = ['xac_thuc', 'tong_quan', 'tai_khoan', 'quan_ly_mo_dun'];

    public function nap(): array
    {
        $ketQua = ['mo_dun' => [], 'menu' => [], 'tuyen' => [], 'tat_ca_mo_dun' => []];
        $danhSach = glob(GOC_DU_AN . '/ung_dung/mo_dun/*/cau_hinh.php') ?: [];
        $trangThaiNgoai = $this->docTrangThaiMoDun();

        foreach ($danhSach as $tep) {
            $tenThuMuc = basename(dirname($tep));
            if (str_starts_with($tenThuMuc, '_')) {
                continue;
            }
            $cauHinh = require $tep;
            $maMoDun = (string)($cauHinh['ma'] ?? '');
            if ($maMoDun === '') {
                continue;
            }

            $kichHoatMacDinh = !empty($cauHinh['kich_hoat']);
            $kichHoat = $this->xacDinhTrangThai($maMoDun, $kichHoatMacDinh, $trangThaiNgoai);
            $cauHinh['kich_hoat'] = $kichHoat;
            $cauHinh['_la_mo_dun_loi'] = in_array($maMoDun, self::MO_DUN_LOI, true);
            $ketQua['tat_ca_mo_dun'][$maMoDun] = $cauHinh;

            if (!$kichHoat) continue;

            $ketQua['mo_dun'][$maMoDun] = $cauHinh;
            if (!empty($cauHinh['anh_huong_menu']) && !empty($cauHinh['menu'])) {
                $mucMenu = $this->chuanHoaMucMenu($cauHinh['menu']);
                if ($mucMenu !== null) {
                    $ketQua['menu'][] = $mucMenu;
                }
            }
            if (!empty($cauHinh['route'])) {
                foreach ($cauHinh['route'] as $route) {
                    $route['_mo_dun_ma'] = $cauHinh['ma'] ?? null;
                    $route['_mo_dun'] = $cauHinh;
                    $ketQua['tuyen'][] = $route;
                }
            }
        }

        usort($ketQua['menu'], static fn($a,$b) => ($a['thu_tu'] ?? 999) <=> ($b['thu_tu'] ?? 999));
        return $ketQua;
    }

    private function docTrangThaiMoDun(): array
    {
        $duongDan = GOC_DU_AN . '/ung_dung/kho_luu/mo_dun/trang_thai.json';
        if (!is_file($duongDan)) {
            return [];
        }
        $noiDung = file_get_contents($duongDan);
        if (!is_string($noiDung) || trim($noiDung) === '') {
            return [];
        }
        $duLieu = json_decode($noiDung, true);
        return is_array($duLieu) ? $duLieu : [];
    }

    private function xacDinhTrangThai(string $maMoDun, bool $macDinh, array $trangThaiNgoai): bool
    {
        if (in_array($maMoDun, self::MO_DUN_LOI, true)) {
            return true;
        }
        if (array_key_exists($maMoDun, $trangThaiNgoai)) {
            return (bool)$trangThaiNgoai[$maMoDun];
        }
        return $macDinh;
    }

    private function chuanHoaMucMenu(array $muc): ?array
    {
        $tieuDe = $muc['tieu_de'] ?? $muc['ten'] ?? null;
        $duongDan = $muc['duong_dan'] ?? '#';
        if (!is_string($tieuDe) || $tieuDe === '') {
            return null;
        }

        $mucMoi = [
            'tieu_de' => $tieuDe,
            'duong_dan' => (string)$duongDan,
            'bieu_tuong' => (string)($muc['bieu_tuong'] ?? 'circle'),
            'quyen' => $muc['quyen'] ?? null,
            'thu_tu' => (int)($muc['thu_tu'] ?? 999),
            'con' => [],
        ];

        $mucCon = $muc['con'] ?? [];
        if (is_array($mucCon)) {
            foreach ($mucCon as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $itemMoi = $this->chuanHoaMucMenu($item);
                if ($itemMoi !== null) {
                    $mucMoi['con'][] = $itemMoi;
                }
            }
            usort($mucMoi['con'], static fn($a, $b) => ($a['thu_tu'] ?? 999) <=> ($b['thu_tu'] ?? 999));
        }

        return $mucMoi;
    }
}
