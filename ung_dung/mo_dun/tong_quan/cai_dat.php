<?php

return static function (string $maMoDun, PDO $pdo): void {
    $pdo->prepare('INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES (1, :ma_quyen) ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen)')
        ->execute(['ma_quyen' => 'tong_quan.xem']);
};
