<?php
$tepNoiDung = $noiDung ?? $noi_dung ?? null;
if (is_string($tepNoiDung) && $tepNoiDung !== '') {
    require $tepNoiDung;
}
