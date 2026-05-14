<?php
declare(strict_types=1);

namespace HeThong;

class KiemTraQuyen
{
    public function coQuyen(?array $nguoiDung, ?string $maQuyen): bool
    {
        if (!$maQuyen) return true;
        if (!$nguoiDung) return false;
        if (($nguoiDung['ma_vai_tro'] ?? null) === 'admin') return true;

        $pdo = CoSoDuLieu::layKetNoi();
        $stm = $pdo->prepare('SELECT 1 FROM quyen_vai_tro WHERE vai_tro_id = :vai_tro_id AND ma_quyen = :ma_quyen LIMIT 1');
        $stm->execute(['vai_tro_id' => $nguoiDung['vai_tro_id'], 'ma_quyen' => $maQuyen]);
        return (bool)$stm->fetchColumn();
    }
}
