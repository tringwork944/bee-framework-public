<?php
declare(strict_types=1);

namespace HeThong;

use PDO;
use Throwable;

class QuanLyMoDun
{
    public const MO_DUN_LOI = ['giao_dien_mac_dinh', 'xac_thuc', 'tong_quan', 'tai_khoan', 'quan_ly_mo_dun'];

    public function dongBo(): void
    {
        $this->taoBangNeuCan();
        $pdo = CoSoDuLieu::layKetNoi();
        $dsDisk = $this->quetMoDunThuMuc();
        $this->xoaBanGhiMoDunKhongConThuMuc($pdo, array_keys($dsDisk));
        foreach ($dsDisk as $ma => $cauHinh) {
            $this->upsertMoDun($pdo, $ma, $cauHinh);
        }
        $this->dongBoMoDunLoi($pdo);
        $this->damBaoMotGiaoDienDangBat($pdo);
    }

    public function duLieuNapHeThong(): array
    {
        $this->dongBo();
        $pdo = CoSoDuLieu::layKetNoi();
        $rows = $pdo->query('SELECT * FROM mo_dun ORDER BY ma ASC')->fetchAll(PDO::FETCH_ASSOC);
        $ketQua = ['mo_dun' => [], 'menu' => [], 'tuyen' => [], 'tat_ca_mo_dun' => []];

        foreach ($rows as $row) {
            $ma = (string)$row['ma'];
            $thuMuc = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
            $cauHinh = $this->docCauHinh($thuMuc);
            if ($cauHinh === null) {
                continue;
            }
            $cauHinh['_legacy'] = !$this->coDayDuVongDoi($thuMuc);
            $cauHinh['_la_mo_dun_loi'] = in_array($ma, self::MO_DUN_LOI, true);
            $cauHinh['_trang_thai'] = (string)($row['trang_thai'] ?? 'chua_cai_dat');
            $cauHinh['_loi'] = (string)($row['loi'] ?? '');
            $cauHinh['_duong_dan'] = $thuMuc;
            $ketQua['tat_ca_mo_dun'][$ma] = $cauHinh;

            if (($row['trang_thai'] ?? '') !== 'dang_bat') {
                continue;
            }

            $ketQua['mo_dun'][$ma] = $cauHinh;
            if (($cauHinh['loai'] ?? '') !== 'giao_dien') {
                foreach ($this->chuanHoaRoute($cauHinh['route'] ?? [], $ma) as $route) {
                    $ketQua['tuyen'][] = $route;
                }
            }
            if (!empty($cauHinh['menu'])) {
                foreach ($this->chuanHoaDanhSachMenu($cauHinh['menu']) as $menu) {
                    $ketQua['menu'][] = $menu;
                }
            }
        }

        return $ketQua;
    }

    public function danhSachTuCSDL(): array
    {
        $this->dongBo();
        $pdo = CoSoDuLieu::layKetNoi();
        $rows = $pdo->query('SELECT * FROM mo_dun ORDER BY ma ASC')->fetchAll(PDO::FETCH_ASSOC);
        $ketQua = [];
        foreach ($rows as $row) {
            $ma = (string)$row['ma'];
            $thuMuc = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
            $cauHinh = $this->docCauHinh($thuMuc);
            if ($cauHinh === null) {
                continue;
            }
            $ketQua[] = [
                'ma' => $ma,
                'ten' => (string)($row['ten'] ?? $ma),
                'mo_ta' => (string)($row['mo_ta'] ?? ''),
                'phien_ban' => (string)($row['phien_ban'] ?? ''),
                'tac_gia' => (string)($row['tac_gia'] ?? ''),
                'website' => (string)($row['website'] ?? ''),
                'loai' => (string)($cauHinh['loai'] ?? 'nghiep_vu'),
                'trang_thai' => (string)($row['trang_thai'] ?? 'chua_cai_dat'),
                'la_mo_dun_loi' => (int)($row['la_mo_dun_loi'] ?? 0) === 1,
                'loi' => (string)($row['loi'] ?? ''),
                'yeu_cau_php' => (string)($cauHinh['yeu_cau_php'] ?? ''),
                'yeu_cau_core' => (string)($cauHinh['yeu_cau_core'] ?? ''),
                'phu_thuoc' => array_values((array)($cauHinh['phu_thuoc'] ?? [])),
                'legacy' => !$this->coDayDuVongDoi($thuMuc),
            ];
        }
        return $ketQua;
    }

    public function thongTinMoDun(string $ma): ?array
    {
        if (!$this->maHopLe($ma)) {
            return null;
        }
        foreach ($this->danhSachTuCSDL() as $m) {
            if ($m['ma'] === $ma) {
                return $m;
            }
        }
        return null;
    }

    public function daDuocKichHoat(string $ma): bool
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare("SELECT trang_thai FROM mo_dun WHERE ma = :ma LIMIT 1");
        $stm->execute(['ma' => $ma]);
        return $stm->fetchColumn() === 'dang_bat';
    }

    public function kiemTraPhuThuoc(string $ma): array
    {
        $cauHinh = $this->docCauHinhTheoMa($ma);
        $thieu = [];
        foreach ((array)($cauHinh['phu_thuoc'] ?? []) as $phuThuoc) {
            if (!is_string($phuThuoc) || $phuThuoc === '') {
                continue;
            }
            if (!$this->daDuocKichHoat($phuThuoc)) {
                $thieu[] = $phuThuoc;
            }
        }
        return $thieu;
    }

    public function kiemTraMoDunPhuThuocDangBat(string $ma): array
    {
        $ds = [];
        foreach ($this->danhSachTuCSDL() as $m) {
            if ($m['ma'] === $ma || $m['trang_thai'] !== 'dang_bat') {
                continue;
            }
            if (in_array($ma, $m['phu_thuoc'], true)) {
                $ds[] = $m['ma'];
            }
        }
        return $ds;
    }

    public function kiemTraMoDunPhuThuocDaCaiDat(string $ma): array
    {
        $ds = [];
        foreach ($this->danhSachTuCSDL() as $m) {
            if ($m['ma'] === $ma || $m['trang_thai'] === 'chua_cai_dat') {
                continue;
            }
            if (in_array($ma, $m['phu_thuoc'], true)) {
                $ds[] = $m['ma'];
            }
        }
        return $ds;
    }

    public function docCauHinhTheoMa(string $ma): array
    {
        $thuMuc = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
        return $this->docCauHinh($thuMuc) ?? [];
    }

    public function duongDanMoDun(string $ma): string
    {
        return GOC_DU_AN . '/ung_dung/mo_dun/' . $ma;
    }

    private function quetMoDunThuMuc(): array
    {
        $thuMucMoDun = GOC_DU_AN . '/ung_dung/mo_dun';
        $thuMucCon = glob($thuMucMoDun . '/*', GLOB_ONLYDIR) ?: [];
        $ketQua = [];
        foreach ($thuMucCon as $thuMuc) {
            $ma = basename($thuMuc);
            if (str_starts_with($ma, '_') || !$this->maHopLe($ma)) {
                continue;
            }
            $cauHinh = $this->docCauHinh($thuMuc);
            if ($cauHinh === null) {
                continue;
            }
            $ketQua[$ma] = $cauHinh;
        }
        return $ketQua;
    }

    private function docCauHinh(string $thuMuc): ?array
    {
        $tep = $thuMuc . '/cau_hinh.php';
        if (!is_file($tep)) {
            return null;
        }
        try {
            $cauHinh = require $tep;
        } catch (Throwable) {
            return null;
        }
        if (!is_array($cauHinh)) {
            return null;
        }
        return $cauHinh;
    }

    private function upsertMoDun(PDO $pdo, string $ma, array $cauHinh): void
    {
        $laLoi = in_array($ma, self::MO_DUN_LOI, true) ? 1 : 0;
        $trangThaiMacDinh = $laLoi === 1 ? 'dang_bat' : 'chua_cai_dat';
        $sql = "INSERT INTO mo_dun (ma, ten, mo_ta, phien_ban, tac_gia, website, duong_dan, trang_thai, la_mo_dun_loi, ngay_cap_nhat)\n                VALUES (:ma, :ten, :mo_ta, :phien_ban, :tac_gia, :website, :duong_dan, :trang_thai, :la_mo_dun_loi, NOW())\n                ON DUPLICATE KEY UPDATE\n                    ten = VALUES(ten),\n                    mo_ta = VALUES(mo_ta),\n                    phien_ban = VALUES(phien_ban),\n                    tac_gia = VALUES(tac_gia),\n                    website = VALUES(website),\n                    duong_dan = VALUES(duong_dan),\n                    la_mo_dun_loi = VALUES(la_mo_dun_loi),\n                    ngay_cap_nhat = NOW()";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            'ma' => $ma,
            'ten' => (string)($cauHinh['ten'] ?? $ma),
            'mo_ta' => (string)($cauHinh['mo_ta'] ?? ''),
            'phien_ban' => (string)($cauHinh['phien_ban'] ?? ''),
            'tac_gia' => (string)($cauHinh['tac_gia'] ?? ''),
            'website' => (string)($cauHinh['website'] ?? ''),
            'duong_dan' => $this->duongDanMoDun($ma),
            'trang_thai' => $trangThaiMacDinh,
            'la_mo_dun_loi' => $laLoi,
        ]);
    }

    private function xoaBanGhiMoDunKhongConThuMuc(PDO $pdo, array $maTrenDia): void
    {
        $rows = $pdo->query('SELECT ma, la_mo_dun_loi FROM mo_dun')->fetchAll(PDO::FETCH_ASSOC);
        $maTrenDia = array_fill_keys($maTrenDia, true);
        $stm = $pdo->prepare('DELETE FROM mo_dun WHERE ma = :ma');

        foreach ($rows as $row) {
            $ma = (string)($row['ma'] ?? '');
            $laMoDunLoi = (int)($row['la_mo_dun_loi'] ?? 0) === 1;
            if ($ma === '' || $laMoDunLoi || isset($maTrenDia[$ma])) {
                continue;
            }
            $stm->execute(['ma' => $ma]);
        }
    }

    private function dongBoMoDunLoi(PDO $pdo): void
    {
        $sql = "UPDATE mo_dun SET trang_thai = 'dang_bat', la_mo_dun_loi = 1,\n                ngay_cai_dat = COALESCE(ngay_cai_dat, NOW()),\n                ngay_kich_hoat = COALESCE(ngay_kich_hoat, NOW()),\n                ngay_cap_nhat = NOW()\n                WHERE ma IN ('xac_thuc','tong_quan','tai_khoan','quan_ly_mo_dun')";
        $pdo->exec($sql);
        $pdo->exec("UPDATE mo_dun SET la_mo_dun_loi = 1, ngay_cap_nhat = NOW() WHERE ma = 'giao_dien_mac_dinh'");
        $this->dongBoQuyenAdminChoMoDunDangBat($pdo);
    }

    private function taoBangNeuCan(): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $pdo->exec("CREATE TABLE IF NOT EXISTS mo_dun (\n            id INT AUTO_INCREMENT PRIMARY KEY,\n            ma VARCHAR(100) NOT NULL UNIQUE,\n            ten VARCHAR(150) NOT NULL,\n            mo_ta TEXT NULL,\n            phien_ban VARCHAR(50) NULL,\n            tac_gia VARCHAR(150) NULL,\n            website VARCHAR(255) NULL,\n            duong_dan VARCHAR(255) NOT NULL,\n            trang_thai ENUM('chua_cai_dat', 'da_cai_dat', 'dang_bat', 'dang_tat', 'loi') DEFAULT 'chua_cai_dat',\n            la_mo_dun_loi TINYINT DEFAULT 0,\n            loi TEXT NULL,\n            ngay_cai_dat DATETIME NULL,\n            ngay_kich_hoat DATETIME NULL,\n            ngay_tat DATETIME NULL,\n            ngay_cap_nhat DATETIME NULL,\n            ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP\n        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $pdo->exec("ALTER TABLE menu_he_thong MODIFY nhom VARCHAR(100) DEFAULT 'nghiep_vu'");
        if (!$this->coCot($pdo, 'menu_he_thong', 'la_menu_he_thong')) {
            $pdo->exec("ALTER TABLE menu_he_thong ADD COLUMN la_menu_he_thong TINYINT DEFAULT 0");
        }
    }

    private function maHopLe(string $ma): bool
    {
        return (bool)preg_match('/^[a-z0-9_]+$/', $ma);
    }

    private function coDayDuVongDoi(string $thuMuc): bool
    {
        return is_file($thuMuc . '/mo_dun.php')
            && is_file($thuMuc . '/cai_dat.php')
            && is_file($thuMuc . '/kich_hoat.php')
            && is_file($thuMuc . '/tat.php')
            && is_file($thuMuc . '/go_cai_dat.php');
    }

    private function chuanHoaRoute(array $routeKhaiBao, string $ma): array
    {
        $ketQua = [];
        foreach ($routeKhaiBao as $route) {
            if (!is_array($route)) {
                continue;
            }

            if (isset($route['phuong_thuc'])) {
                $route['_mo_dun_ma'] = $ma;
                $ketQua[] = $route;
                continue;
            }

            if (count($route) < 3 || !is_string($route[0]) || !is_string($route[1])) {
                continue;
            }

            $xuLy = $route[2];
            if (is_string($xuLy) && str_contains($xuLy, '@')) {
                [$lop, $ham] = explode('@', $xuLy, 2);
                $xuLy = ['MoDun\\' . ucfirst($ma) . '\\DieuKhien\\' . $lop, $ham];
            }
            $ketQua[] = [
                'phuong_thuc' => strtoupper($route[0]),
                'duong_dan' => $route[1],
                'xu_ly' => $xuLy,
                'quyen' => $route[3] ?? null,
                'yeu_cau_dang_nhap' => true,
                '_mo_dun_ma' => $ma,
            ];
        }

        return $ketQua;
    }

    private function chuanHoaDanhSachMenu(mixed $menu): array
    {
        if (!is_array($menu)) {
            return [];
        }
        return array_is_list($menu) ? $menu : [$menu];
    }

    private function dongBoQuyenAdminChoMoDunDangBat(PDO $pdo): void
    {
        $rows = $pdo->query("SELECT ma FROM mo_dun WHERE trang_thai = 'dang_bat'")->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare('INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES (1, :ma_quyen) ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen)');
        foreach ($rows as $row) {
            $ma = (string)($row['ma'] ?? '');
            if ($ma === '') {
                continue;
            }
            $quyen = $this->docCauHinhTheoMa($ma)['quyen'] ?? [];
            if (array_is_list($quyen)) {
                foreach ($quyen as $q) {
                    if (is_string($q) && $q !== '') {
                        $stmt->execute(['ma_quyen' => $q]);
                    }
                }
            } else {
                foreach ($quyen as $q => $moTa) {
                    if (is_string($q) && $q !== '') {
                        $stmt->execute(['ma_quyen' => $q]);
                    }
                }
            }
        }
    }

    private function coCot(PDO $pdo, string $bang, string $cot): bool
    {
        $stm = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :bang AND COLUMN_NAME = :cot");
        $stm->execute(['bang' => $bang, 'cot' => $cot]);
        return (int)$stm->fetchColumn() > 0;
    }

    private function damBaoMotGiaoDienDangBat(PDO $pdo): void
    {
        $rows = $pdo->query("SELECT ma, trang_thai FROM mo_dun ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        $giaoDienDangBat = [];
        foreach ($rows as $row) {
            $ma = (string)$row['ma'];
            $cauHinh = $this->docCauHinhTheoMa($ma);
            if (($cauHinh['loai'] ?? '') !== 'giao_dien') {
                continue;
            }
            if (($row['trang_thai'] ?? '') === 'dang_bat') {
                $giaoDienDangBat[] = $ma;
            }
        }

        if ($giaoDienDangBat === []) {
            $stm = $pdo->prepare("UPDATE mo_dun SET trang_thai = 'dang_bat', ngay_kich_hoat = COALESCE(ngay_kich_hoat, NOW()), ngay_cap_nhat = NOW() WHERE ma = 'giao_dien_mac_dinh'");
            $stm->execute();
            QuanLyGiaoDien::kichHoatTheoMoDun('giao_dien_mac_dinh');
            return;
        }

        $giu = $giaoDienDangBat[0];
        $stm = $pdo->prepare("UPDATE mo_dun SET trang_thai = 'dang_tat', ngay_tat = NOW(), ngay_cap_nhat = NOW() WHERE ma = :ma");
        foreach (array_slice($giaoDienDangBat, 1) as $ma) {
            $stm->execute(['ma' => $ma]);
        }
        QuanLyGiaoDien::kichHoatTheoMoDun($giu);
    }
}
