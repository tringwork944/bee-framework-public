<?php
declare(strict_types=1);

define('GOC_DU_AN', dirname(__DIR__));

require GOC_DU_AN . '/ung_dung/khoi_tao.php';

$khoiDong = new HeThong\KhoiDong();
$khoiDong->chay();