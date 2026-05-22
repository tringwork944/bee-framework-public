<?php
declare(strict_types=1);

namespace HeThong;

class DangKyDichVuMoDun
{
    private static array $dichVu = [
        'model' => [],
        'command' => [],
        'widget' => [],
        'dashboard_card' => [],
        'report_provider' => [],
    ];

    public static function dangKy(string $loai, string $maMoDun, string $ten, mixed $dichVu): void
    {
        if (!isset(self::$dichVu[$loai])) {
            self::$dichVu[$loai] = [];
        }
        self::$dichVu[$loai][$maMoDun][$ten] = $dichVu;
    }

    public static function layTheoLoai(string $loai): array
    {
        return self::$dichVu[$loai] ?? [];
    }

    public static function layTatCa(): array
    {
        return self::$dichVu;
    }
}
