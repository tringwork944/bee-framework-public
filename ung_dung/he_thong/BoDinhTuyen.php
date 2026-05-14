<?php
declare(strict_types=1);

namespace HeThong;

class BoDinhTuyen
{
    private array $tuyen = [];

    public function dangKy(string $phuongThuc, string $mau, callable $hamXuLy, array $tuyChon = []): void
    {
        $mauSach = rtrim($mau, '/');
        if ($mauSach === '') {
            $mauSach = '/';
        }
        $this->tuyen[] = [
            'phuong_thuc' => strtoupper($phuongThuc),
            'mau' => $mauSach,
            'regex' => '#^' . preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $mauSach) . '$#',
            'ham_xu_ly' => $hamXuLy,
            'tuy_chon' => $tuyChon,
        ];
    }

    public function xuLy(YeuCau $yeuCau): void
    {
        $path = rtrim($yeuCau->duongDan(), '/');
        if ($path === '') $path = '/';
        foreach ($this->tuyen as $t) {
            if ($t['phuong_thuc'] !== $yeuCau->phuongThuc()) continue;
            if (!preg_match($t['regex'], $path, $khop)) continue;
            $thamSo = array_filter($khop, static fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);
            $ketQua = ($t['ham_xu_ly'])($yeuCau, $thamSo, $t['tuy_chon']);
            if (is_string($ketQua)) echo $ketQua;
            return;
        }
        http_response_code(404);
        hien_thi('loi/404.php');
    }
}
