<?php

return static function (string $maMoDun, PDO $pdo): void {
    $pdo->exec("UPDATE giao_dien SET dang_kich_hoat = 0");
    $stm = $pdo->prepare("UPDATE giao_dien SET dang_kich_hoat = 1 WHERE mo_dun_ma = :ma");
    $stm->execute(['ma' => $maMoDun]);
};
