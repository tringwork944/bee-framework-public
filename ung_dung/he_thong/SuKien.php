<?php
declare(strict_types=1);

namespace HeThong;

class SuKien
{
    private static array $danhSach = [];

    public static function themHanhDong(string $tenHook, callable $callback, int $uuTien = 10): void
    {
        self::$danhSach[$tenHook][$uuTien][] = $callback;
    }

    public static function goiHanhDong(string $tenHook, mixed ...$thamSo): void
    {
        if (empty(self::$danhSach[$tenHook])) {
            return;
        }

        ksort(self::$danhSach[$tenHook]);
        foreach (self::$danhSach[$tenHook] as $danhSachTheoUuTien) {
            foreach ($danhSachTheoUuTien as $callback) {
                $callback(...$thamSo);
            }
        }
    }

    public static function xoaHanhDong(string $tenHook, callable $callback): void
    {
        if (empty(self::$danhSach[$tenHook])) {
            return;
        }

        foreach (self::$danhSach[$tenHook] as $uuTien => $danhSachTheoUuTien) {
            foreach ($danhSachTheoUuTien as $index => $daDangKy) {
                if ($daDangKy === $callback) {
                    unset(self::$danhSach[$tenHook][$uuTien][$index]);
                }
            }
            if (empty(self::$danhSach[$tenHook][$uuTien])) {
                unset(self::$danhSach[$tenHook][$uuTien]);
            }
        }

        if (empty(self::$danhSach[$tenHook])) {
            unset(self::$danhSach[$tenHook]);
        }
    }
}
