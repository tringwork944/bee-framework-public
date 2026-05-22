<?php

use HeThong\BoLoc;
use HeThong\SuKien;

SuKien::themHanhDong('he_thong.khoi_dong', function (): void {
    // logic khoi dong mo dun
});

BoLoc::themBoLoc('menu.truoc_hien_thi', function (array $menu): array {
    return $menu;
});
