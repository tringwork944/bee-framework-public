<?php

return static function (string $maMoDun, PDO $pdo): void {
    $stm = $pdo->prepare("UPDATE menu_he_thong SET trang_thai = 1, ngay_cap_nhat = NOW() WHERE mo_dun_ma = :ma");
    $stm->execute(['ma' => $maMoDun]);
};
