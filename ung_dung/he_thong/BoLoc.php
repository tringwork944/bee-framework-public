<?php
declare(strict_types=1);

namespace HeThong;

class BoLoc
{
    private static array $danhSach = [];

    public static function themBoLoc(string $tenHook, callable $callback, int $uuTien = 10): void
    {
        self::$danhSach[$tenHook][$uuTien][] = $callback;
    }

    public static function apDungBoLoc(string $tenHook, mixed $giaTri, mixed ...$thamSo): mixed
    {
        if (empty(self::$danhSach[$tenHook])) {
            return $giaTri;
        }

        ksort(self::$danhSach[$tenHook]);
        $ketQua = $giaTri;
        foreach (self::$danhSach[$tenHook] as $danhSachTheoUuTien) {
            foreach ($danhSachTheoUuTien as $callback) {
                $ketQua = $callback($ketQua, ...$thamSo);
            }
        }
        return $ketQua;
    }

    public static function xoaBoLoc(string $tenHook, callable $callback): void
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
