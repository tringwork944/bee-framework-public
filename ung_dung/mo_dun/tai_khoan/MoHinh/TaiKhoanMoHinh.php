<?php
declare(strict_types=1);

namespace MoDun\TaiKhoan\MoHinh;

use HeThong\CoSoDuLieu;
use PDO;

class TaiKhoanMoHinh
{
    private static ?array $cotTaiKhoan = null;

    public function layDanhSach(): array
    {
        return CoSoDuLieu::layKetNoi()
            ->query('SELECT tk.id, tk.ho_ten, tk.email, tk.trang_thai, tk.vai_tro_id, vt.ten AS ten_vai_tro FROM tai_khoan tk LEFT JOIN vai_tro vt ON vt.id = tk.vai_tro_id ORDER BY tk.id DESC')
            ->fetchAll();
    }

    public function timTheoId(int $id, bool $gomMatKhau = false): ?array
    {
        $cot = ['id', 'vai_tro_id', 'ho_ten', 'email', 'trang_thai'];
        if ($this->coCot('so_dien_thoai')) $cot[] = 'so_dien_thoai';
        if ($this->coCot('ghi_chu')) $cot[] = 'ghi_chu';
        if ($gomMatKhau) $cot[] = 'mat_khau';

        $sql = 'SELECT ' . implode(', ', $cot) . ' FROM tai_khoan WHERE id = :id LIMIT 1';
        $stm = CoSoDuLieu::layKetNoi()->prepare($sql);
        $stm->execute(['id' => $id]);
        $duLieu = $stm->fetch(PDO::FETCH_ASSOC);
        return is_array($duLieu) ? $duLieu : null;
    }

    public function emailDaTonTaiKhacId(string $email, int $id): bool
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('SELECT 1 FROM tai_khoan WHERE email = :email AND id <> :id LIMIT 1');
        $stm->execute(['email' => $email, 'id' => $id]);
        return (bool)$stm->fetchColumn();
    }

    public function emailDaTonTai(string $email): bool
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('SELECT 1 FROM tai_khoan WHERE email = :email LIMIT 1');
        $stm->execute(['email' => $email]);
        return (bool)$stm->fetchColumn();
    }

    public function capNhatChoAdmin(int $id, string $hoTen, int $trangThai): void
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('UPDATE tai_khoan SET ho_ten = :ho_ten, trang_thai = :trang_thai WHERE id = :id');
        $stm->execute(['id' => $id, 'ho_ten' => $hoTen, 'trang_thai' => $trangThai]);
    }

    public function layDanhSachVaiTro(): array
    {
        return CoSoDuLieu::layKetNoi()->query('SELECT id, ma, ten FROM vai_tro ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function capNhatTaiKhoanDayDu(int $id, array $duLieu): void
    {
        $tap = [
            'ho_ten = :ho_ten',
            'email = :email',
            'vai_tro_id = :vai_tro_id',
            'trang_thai = :trang_thai',
        ];
        $thamSo = [
            'id' => $id,
            'ho_ten' => $duLieu['ho_ten'],
            'email' => $duLieu['email'],
            'vai_tro_id' => $duLieu['vai_tro_id'],
            'trang_thai' => $duLieu['trang_thai'],
        ];

        if ($this->coCot('so_dien_thoai')) {
            $tap[] = 'so_dien_thoai = :so_dien_thoai';
            $thamSo['so_dien_thoai'] = $duLieu['so_dien_thoai'] ?? null;
        }
        if ($this->coCot('ghi_chu')) {
            $tap[] = 'ghi_chu = :ghi_chu';
            $thamSo['ghi_chu'] = $duLieu['ghi_chu'] ?? null;
        }
        if (!empty($duLieu['mat_khau_moi_hash'])) {
            $tap[] = 'mat_khau = :mat_khau';
            $thamSo['mat_khau'] = $duLieu['mat_khau_moi_hash'];
        }
        if ($this->coCot('ngay_cap_nhat')) {
            $tap[] = 'ngay_cap_nhat = NOW()';
        }

        $sql = 'UPDATE tai_khoan SET ' . implode(', ', $tap) . ' WHERE id = :id';
        $stm = CoSoDuLieu::layKetNoi()->prepare($sql);
        $stm->execute($thamSo);
    }

    public function taoTaiKhoan(array $duLieu): void
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('INSERT INTO tai_khoan (vai_tro_id, ho_ten, email, mat_khau, trang_thai, ngay_cap_nhat) VALUES (:vai_tro_id, :ho_ten, :email, :mat_khau, :trang_thai, NOW())');
        $stm->execute([
            'vai_tro_id' => (int)$duLieu['vai_tro_id'],
            'ho_ten' => (string)$duLieu['ho_ten'],
            'email' => (string)$duLieu['email'],
            'mat_khau' => (string)$duLieu['mat_khau'],
            'trang_thai' => (int)$duLieu['trang_thai'],
        ]);
    }

    public function xoaTaiKhoan(int $id): void
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('DELETE FROM tai_khoan WHERE id = :id');
        $stm->execute(['id' => $id]);
    }

    public function coCot(string $tenCot): bool
    {
        if (self::$cotTaiKhoan === null) {
            $stm = CoSoDuLieu::layKetNoi()->query('SHOW COLUMNS FROM tai_khoan');
            self::$cotTaiKhoan = array_map(static fn($r) => (string)$r['Field'], $stm->fetchAll(PDO::FETCH_ASSOC));
        }
        return in_array($tenCot, self::$cotTaiKhoan, true);
    }
}
