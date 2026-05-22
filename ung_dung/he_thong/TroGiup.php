<?php
declare(strict_types=1);

function env(string $khoa, ?string $macDinh = null): ?string
{
    static $duLieu = null;
    if ($duLieu === null) {
        $duLieu = [];
        $tep = GOC_DU_AN . '/.env';
        if (is_file($tep)) {
            foreach (file($tep, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $dong) {
                if (str_starts_with(trim($dong), '#') || !str_contains($dong, '=')) {
                    continue;
                }
                [$k, $v] = explode('=', $dong, 2);
                $duLieu[trim($k)] = trim($v);
            }
        }
    }
    return $duLieu[$khoa] ?? $_ENV[$khoa] ?? $macDinh;
}

function hien_thi(string $tep, array $duLieu = []): void
{
    extract($duLieu, EXTR_SKIP);
    $duongDan = \HeThong\QuanLyGiaoDien::giaiQuyetTepGiaoDien($tep);
    require $duongDan;
}

function chuyen_huong(string $duongDan): never
{
    header('Location: ' . $duongDan);
    exit;
}

function bao_mat_chuoi(?string $giaTri): string
{
    return htmlspecialchars((string)$giaTri, ENT_QUOTES, 'UTF-8');
}

function csrf_tao(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_kiem_tra(?string $token): bool
{
    return is_string($token) && isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}

function duong_dan_hien_tai(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    return rtrim($path, '/') ?: '/';
}

function duong_dan_hoat_dong(string $duongDanMenu): bool
{
    $hienTai = duong_dan_hien_tai();
    $menu = rtrim($duongDanMenu, '/') ?: '/';
    return $hienTai === $menu || ($menu !== '/' && str_starts_with($hienTai, $menu . '/'));
}

function url_tai_nguyen(string $duongDan): string
{
    $duongDan = ltrim($duongDan, '/');
    if (isset($_SERVER['DOCUMENT_ROOT']) && is_dir(rtrim((string)$_SERVER['DOCUMENT_ROOT'], '/\\') . DIRECTORY_SEPARATOR . 'dist')) {
        return '/' . $duongDan;
    }
    return '/cong_khai/' . $duongDan;
}

function hien_thi_bo_cuc(string $tepNoiDung, array $duLieu = [], ?string $boCuc = null): void
{
    $noiDung = $tepNoiDung;
    $boCuc = $boCuc ?? ($GLOBALS['bo_cuc_mac_dinh'] ?? 'chinh');
    \HeThong\QuanLyGiaoDien::renderLayout($boCuc, array_merge($duLieu, ['noiDung' => $noiDung]));
}

function render_layout(string $boCuc, array $duLieu = []): void
{
    \HeThong\QuanLyGiaoDien::renderLayout($boCuc, $duLieu);
}

function render_partial(string $partial, array $duLieu = []): void
{
    \HeThong\QuanLyGiaoDien::renderPartial($partial, $duLieu);
}

function co_quyen(?string $maQuyen): bool
{
    $nguoiDung = $_SESSION['nguoi_dung'] ?? null;
    return (new \HeThong\KiemTraQuyen())->coQuyen($nguoiDung, $maQuyen);
}
