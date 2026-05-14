<?php
declare(strict_types=1);

namespace HeThong;

class Phien
{
    public static function batDau(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
