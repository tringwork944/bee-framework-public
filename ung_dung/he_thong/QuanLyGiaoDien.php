<?php
declare(strict_types=1);

namespace HeThong;

use PDO;

class QuanLyGiaoDien
{
    public function __construct(
        private readonly QuanLyMenu $quanLyMenu,
        private readonly QuanLyTaiNguyen $quanLyTaiNguyen
    ) {
    }

    public static function khoiTaoHeGiaoDien(): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $pdo->exec("CREATE TABLE IF NOT EXISTS giao_dien (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ma VARCHAR(100) NOT NULL UNIQUE,
            ten VARCHAR(150) NOT NULL,
            mo_dun_ma VARCHAR(100) NOT NULL,
            phien_ban VARCHAR(50) NULL,
            dang_kich_hoat TINYINT DEFAULT 0,
            la_mac_dinh TINYINT DEFAULT 0,
            ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->prepare("INSERT INTO giao_dien (ma, ten, mo_dun_ma, phien_ban, dang_kich_hoat, la_mac_dinh)
                VALUES ('giao_dien_mac_dinh', 'Giao dien mac dinh', 'giao_dien_mac_dinh', '1.0.0', 1, 1)
                ON DUPLICATE KEY UPDATE
                ten = VALUES(ten), mo_dun_ma = VALUES(mo_dun_ma), phien_ban = VALUES(phien_ban)")
            ->execute();

        $co = (int)$pdo->query('SELECT COUNT(*) FROM giao_dien WHERE dang_kich_hoat = 1')->fetchColumn();
        if ($co === 0) {
            $pdo->exec("UPDATE giao_dien SET dang_kich_hoat = 0");
            $pdo->exec("UPDATE giao_dien SET dang_kich_hoat = 1 WHERE ma = 'giao_dien_mac_dinh'");
        }
    }

    public static function kichHoatTheoMoDun(string $maMoDun): void
    {
        $pdo = CoSoDuLieu::layKetNoi();
        $pdo->prepare("INSERT INTO giao_dien (ma, ten, mo_dun_ma, dang_kich_hoat, la_mac_dinh)
                VALUES (:ma, :ten, :mo_dun_ma, 0, 0)
                ON DUPLICATE KEY UPDATE mo_dun_ma = VALUES(mo_dun_ma)")
            ->execute(['ma' => $maMoDun, 'ten' => str_replace('_', ' ', ucfirst($maMoDun)), 'mo_dun_ma' => $maMoDun]);
        $pdo->exec("UPDATE giao_dien SET dang_kich_hoat = 0");
        $stm = $pdo->prepare("UPDATE giao_dien SET dang_kich_hoat = 1 WHERE mo_dun_ma = :mo_dun_ma");
        $stm->execute(['mo_dun_ma' => $maMoDun]);
    }

    public static function layGiaoDienDangKichHoat(): array
    {
        self::khoiTaoHeGiaoDien();
        $pdo = CoSoDuLieu::layKetNoi();
        $row = $pdo->query("SELECT * FROM giao_dien WHERE dang_kich_hoat = 1 ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : ['ma' => 'giao_dien_mac_dinh', 'mo_dun_ma' => 'giao_dien_mac_dinh'];
    }

    public static function gocGiaoDienDangKichHoat(): string
    {
        $gd = self::layGiaoDienDangKichHoat();
        $maMoDun = (string)($gd['mo_dun_ma'] ?? 'giao_dien_mac_dinh');
        return GOC_DU_AN . '/ung_dung/mo_dun/' . $maMoDun . '/giao_dien';
    }

    public static function giaiQuyetTepGiaoDien(string $tep): string
    {
        $tepTuongDoi = ltrim($tep, '/');
        $ungVienMoi = self::gocGiaoDienDangKichHoat() . '/' . $tepTuongDoi;
        if (is_file($ungVienMoi)) {
            return $ungVienMoi;
        }
        throw new \RuntimeException('Khong tim thay tep giao dien: ' . $tepTuongDoi);
    }

    public static function renderLayout(string $boCuc, array $duLieu): void
    {
        $tep = self::giaiQuyetTepGiaoDien('bo_cuc/' . $boCuc . '.php');
        if (!is_file($tep)) {
            $tep = self::giaiQuyetTepGiaoDien('bo_cuc/chinh.php');
        }
        extract($duLieu, EXTR_SKIP);
        require $tep;
    }

    public static function renderPartial(string $partial, array $duLieu = []): void
    {
        $tep = self::giaiQuyetTepGiaoDien('thanh_phan/' . $partial . '.php');
        extract($duLieu, EXTR_SKIP);
        require $tep;
    }

    public function chuanBi(array $duLieuMoDun, array $tuyen, ?array $nguoiDung, string $duongDanHienTai): void
    {
        $moDun = is_array($tuyen['_mo_dun'] ?? null) ? $tuyen['_mo_dun'] : [];
        $giaoDien = is_array($moDun['giao_dien'] ?? null) ? $moDun['giao_dien'] : [];

        $breadcrumb = $this->taoBreadcrumb($giaoDien, $duongDanHienTai);
        $tieuDe = (string)($giaoDien['tieu_de'] ?? $moDun['ten'] ?? 'Bee Frame');

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
        $auto = [['tieu_de' => 'Tong quan', 'duong_dan' => '/']];
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
