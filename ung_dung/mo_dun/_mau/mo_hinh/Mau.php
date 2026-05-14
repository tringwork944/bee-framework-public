<?php
declare(strict_types=1);

namespace MoDun\Mau\MoHinh;

use HeThong\CoSoDuLieu;
use PDO;

class Mau
{
    public function layDanhSach(): array
    {
        // Vi du bang: mau_du_lieu(id, ten, tao_luc)
        $sql = 'SELECT id, ten, tao_luc FROM mau_du_lieu ORDER BY id DESC';
        return CoSoDuLieu::layKetNoi()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function timTheoId(int $id): ?array
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('SELECT id, ten, tao_luc FROM mau_du_lieu WHERE id = :id LIMIT 1');
        $stm->execute(['id' => $id]);
        $r = $stm->fetch(PDO::FETCH_ASSOC);
        return is_array($r) ? $r : null;
    }

    public function tao(array $duLieu): int
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('INSERT INTO mau_du_lieu (ten) VALUES (:ten)');
        $stm->execute(['ten' => $duLieu['ten'] ?? '']);
        return (int)CoSoDuLieu::layKetNoi()->lastInsertId();
    }

    public function capNhat(int $id, array $duLieu): bool
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('UPDATE mau_du_lieu SET ten = :ten WHERE id = :id');
        return $stm->execute(['id' => $id, 'ten' => $duLieu['ten'] ?? '']);
    }

    public function xoa(int $id): bool
    {
        $stm = CoSoDuLieu::layKetNoi()->prepare('DELETE FROM mau_du_lieu WHERE id = :id');
        return $stm->execute(['id' => $id]);
    }
}
