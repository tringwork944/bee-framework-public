<?php
declare(strict_types=1);

namespace HeThong;

use PDO;

class QuanLyMenu
{
    private const MENU_LOI = ['tong_quan', 'tai_khoan', 'quan_ly_mo_dun'];
    private const MENU_CAI_DAT_MAC_DINH = ['tai_khoan', 'quan_ly_mo_dun'];

    public function __construct(private readonly KiemTraQuyen $kiemTraQuyen)
    {
    }

    public function dongBoMenuTuMoDun(array $tatCaMoDun): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        foreach ($tatCaMoDun as $moDun) {
            if (!is_array($moDun) || empty($moDun['ma'])) continue;
            $maMoDun = (string)$moDun['ma'];
            $trangThaiKhaiBao = (string)($moDun['_trang_thai'] ?? '');
            $trangThaiMoDun = $this->xacDinhTrangThaiMoDun($moDun);
            $this->capNhatTrangThaiMenuTheoMoDun($pdo, $maMoDun, $trangThaiMoDun);

            if ($trangThaiKhaiBao === 'chua_cai_dat') {
                continue;
            }
            if (empty($moDun['menu'])) continue;
            $menus = $this->chuanHoaMenuSeed($moDun['menu']);
            foreach ($menus as $muc) {
                $this->upsertMenu($pdo, $muc, $maMoDun, null, $trangThaiMoDun);
            }
        }
    }

    public function layMenuChoGiaoDien(?array $nguoiDung, string $duongDanHienTai): array
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->query("SELECT id, ma, mo_dun_ma, nhom, cha_id, tieu_de, bieu_tuong, duong_dan, quyen, thu_tu, hien_thi, trang_thai
                            FROM menu_he_thong
                            WHERE trang_thai = 1 AND hien_thi = 1
                            ORDER BY thu_tu ASC, id ASC");
        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $maQuyen = is_string($r['quyen'] ?? null) ? $r['quyen'] : null;
            if (!$this->kiemTraQuyen->coQuyen($nguoiDung, $maQuyen)) continue;
            $map[(int)$r['id']] = [
                'id' => (int)$r['id'],
                'ma' => (string)$r['ma'],
                'nhom' => isset($r['nhom']) ? (string)$r['nhom'] : null,
                'cha_id' => $r['cha_id'] !== null ? (int)$r['cha_id'] : null,
                'tieu_de' => (string)$r['tieu_de'],
                'bieu_tuong' => $this->chuanHoaIcon((string)($r['bieu_tuong'] ?? 'ti ti-circle')),
                'duong_dan' => (string)($r['duong_dan'] ?? '#'),
                'thu_tu' => (int)($r['thu_tu'] ?? 999),
                'active' => false,
                'con' => [],
            ];
        }

        $root = [];
        foreach ($map as $id => &$item) {
            $chaId = $item['cha_id'];
            if ($chaId !== null && isset($map[$chaId])) {
                $map[$chaId]['con'][] = &$item;
            } else {
                $root[] = &$item;
            }
        }
        unset($item);

        $this->danhDauActive($root, rtrim($duongDanHienTai, '/') ?: '/');
        $menu = $this->gomNhomCaiDat($root);
        $menu = BoLoc::apDungBoLoc('menu.truoc_hien_thi', $menu, $nguoiDung, $duongDanHienTai);
        SuKien::goiHanhDong('menu.sau_hien_thi', $menu, $nguoiDung, $duongDanHienTai);
        return $menu;
    }

    private function chuanHoaMenuSeed(array $menu): array
    {
        $laDanhSach = array_is_list($menu);
        $items = $laDanhSach ? $menu : [$menu];
        $ketQua = [];
        foreach ($items as $m) {
            if (!is_array($m)) continue;
            if (empty($m['ma']) && !empty($m['ten'])) {
                $m['ma'] = strtolower((string)preg_replace('/[^a-z0-9_]/', '_', $m['ten']));
            }
            if (empty($m['ma']) || !preg_match('/^[a-z0-9_]+$/', (string)$m['ma'])) continue;
            $ketQua[] = [
                'ma' => (string)$m['ma'],
                'tieu_de' => (string)($m['tieu_de'] ?? $m['ten'] ?? ''),
                'bieu_tuong' => (string)($m['bieu_tuong'] ?? 'ti ti-circle'),
                'duong_dan' => (string)($m['duong_dan'] ?? '#'),
                'quyen' => isset($m['quyen']) ? (string)$m['quyen'] : null,
                'nhom' => isset($m['nhom']) ? (string)$m['nhom'] : null,
                'thu_tu' => (int)($m['thu_tu'] ?? 999),
                'hien_thi' => !isset($m['hien_thi']) || (bool)$m['hien_thi'],
                'con' => $this->chuanHoaMenuSeed(is_array($m['con'] ?? null) ? $m['con'] : []),
            ];
        }
        return $ketQua;
    }

    private function upsertMenu(PDO $pdo, array $muc, string $maMoDun, ?int $chaId, int $trangThai): void
    {
        $sql = "INSERT INTO menu_he_thong (ma, mo_dun_ma, nhom, cha_id, tieu_de, bieu_tuong, duong_dan, quyen, thu_tu, hien_thi, trang_thai, la_menu_he_thong, ngay_cap_nhat)
                VALUES (:ma, :mo_dun_ma, :nhom, :cha_id, :tieu_de, :bieu_tuong, :duong_dan, :quyen, :thu_tu, :hien_thi, :trang_thai, :la_menu_he_thong, NOW())
                ON DUPLICATE KEY UPDATE
                    mo_dun_ma = VALUES(mo_dun_ma),
                    nhom = VALUES(nhom),
                    cha_id = VALUES(cha_id),
                    tieu_de = VALUES(tieu_de),
                    bieu_tuong = VALUES(bieu_tuong),
                    duong_dan = VALUES(duong_dan),
                    quyen = VALUES(quyen),
                    thu_tu = VALUES(thu_tu),
                    hien_thi = VALUES(hien_thi),
                    trang_thai = VALUES(trang_thai),
                    la_menu_he_thong = VALUES(la_menu_he_thong),
                    ngay_cap_nhat = NOW()";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            'ma' => $muc['ma'],
            'mo_dun_ma' => $maMoDun,
            'nhom' => $muc['nhom'] ?? 'nghiep_vu',
            'cha_id' => $chaId,
            'tieu_de' => $muc['tieu_de'],
            'bieu_tuong' => $muc['bieu_tuong'],
            'duong_dan' => $muc['duong_dan'],
            'quyen' => $muc['quyen'],
            'thu_tu' => $muc['thu_tu'],
            'hien_thi' => $muc['hien_thi'] ? 1 : 0,
            'trang_thai' => $trangThai,
            'la_menu_he_thong' => in_array($maMoDun, self::MENU_LOI, true) ? 1 : 0,
        ]);

        $id = (int)$pdo->lastInsertId();
        if ($id <= 0) {
            $q = $pdo->prepare('SELECT id FROM menu_he_thong WHERE ma = :ma LIMIT 1');
            $q->execute(['ma' => $muc['ma']]);
            $id = (int)$q->fetchColumn();
        }

        foreach ($muc['con'] as $con) {
            $this->upsertMenu($pdo, $con, $maMoDun, $id > 0 ? $id : null, $trangThai);
        }
    }

    private function capNhatTrangThaiMenuTheoMoDun(PDO $pdo, string $maMoDun, int $trangThai): void
    {
        $stm = $pdo->prepare('UPDATE menu_he_thong SET trang_thai = :trang_thai, ngay_cap_nhat = NOW() WHERE mo_dun_ma = :mo_dun_ma');
        $stm->execute(['trang_thai' => $trangThai, 'mo_dun_ma' => $maMoDun]);
    }

    private function xacDinhTrangThaiMoDun(array $moDun): int
    {
        $trangThai = (string)($moDun['_trang_thai'] ?? '');
        if ($trangThai !== '') {
            return $trangThai === 'dang_bat' ? 1 : 0;
        }
        return !empty($moDun['kich_hoat']) ? 1 : 0;
    }

    private function chuanHoaIcon(string $icon): string
    {
        if (str_starts_with($icon, 'ti ')) return $icon;
        if (str_starts_with($icon, 'ti-')) return 'ti ' . $icon;
        return 'ti ti-' . $icon;
    }

    private function danhDauActive(array &$nodes, string $duongDanHienTai): bool
    {
        $coActive = false;
        foreach ($nodes as &$n) {
            $path = rtrim((string)$n['duong_dan'], '/') ?: '/';
            $active = $path !== '#' && ($duongDanHienTai === $path || ($path !== '/' && str_starts_with($duongDanHienTai, $path . '/')));
            if (!empty($n['con'])) {
                $active = $this->danhDauActive($n['con'], $duongDanHienTai) || $active;
            }
            $n['active'] = $active;
            $coActive = $coActive || $active;
        }
        return $coActive;
    }

    private function gomNhomCaiDat(array $root): array
    {
        $menuCaiDat = [];
        $menuNghiepVu = [];
        foreach ($root as $item) {
            $laCaiDat = (($item['nhom'] ?? null) === 'cai_dat') || in_array((string)$item['ma'], self::MENU_CAI_DAT_MAC_DINH, true);
            if ($laCaiDat) {
                $menuCaiDat[] = $item;
            } else {
                $menuNghiepVu[] = $item;
            }
        }

        if ($menuCaiDat === []) {
            return $menuNghiepVu;
        }

        usort($menuCaiDat, static fn($a, $b) => (($a['thu_tu'] ?? 999) <=> ($b['thu_tu'] ?? 999)));
        $active = false;
        foreach ($menuCaiDat as $m) {
            if (!empty($m['active'])) {
                $active = true;
                break;
            }
        }

        $menuNghiepVu[] = [
            'id' => 0,
            'ma' => 'cai_dat',
            'nhom' => null,
            'cha_id' => null,
            'tieu_de' => 'Cai dat',
            'bieu_tuong' => 'ti ti-settings',
            'duong_dan' => '#',
            'thu_tu' => 999999,
            'active' => $active,
            'con' => $menuCaiDat,
        ];

        return $menuNghiepVu;
    }
}
