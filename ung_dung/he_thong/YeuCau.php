<?php
declare(strict_types=1);

namespace HeThong;

class YeuCau
{
    public function phuongThuc(): string { return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'); }
    public function duongDan(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        return rtrim($path, '/') ?: '/';
    }
    public function dauVao(string $khoa, mixed $macDinh = null): mixed
    {
        return $_POST[$khoa] ?? $_GET[$khoa] ?? $macDinh;
    }
}
