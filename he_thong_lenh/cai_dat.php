<?php

declare(strict_types=1);

const CAI_DAT_GOC = __DIR__ . '/..';

function noi_dung_mau_env(): string
{
    return implode(PHP_EOL, [
        'UNG_DUNG_TEN=Framework',
        'UNG_DUNG_MOI_TRUONG=local',
        'UNG_DUNG_DEBUG=true',
        'UNG_DUNG_URL=http://localhost',
        'UNG_DUNG_MUOI=thay-doi-chuoi-bi-mat',
        '',
        'DB_MAY_CHU=127.0.0.1',
        'DB_CONG=3306',
        'DB_TEN=framework',
        'DB_NGUOI_DUNG=root',
        'DB_MAT_KHAU=',
        'DB_BANG_MA=utf8mb4',
        '',
    ]);
}

function in_dong(string $noiDung): void
{
    echo $noiDung . PHP_EOL;
}

function kiem_tra_php(): bool
{
    $ok = version_compare(PHP_VERSION, '8.1.0', '>=');
    in_dong($ok ? '[OK] PHP version: ' . PHP_VERSION : '[FAIL] PHP >= 8.1 required. Current: ' . PHP_VERSION);
    return $ok;
}

function kiem_tra_pdo(): bool
{
    $ok = extension_loaded('pdo');
    in_dong($ok ? '[OK] PDO extension loaded' : '[FAIL] PDO extension missing');
    return $ok;
}

function dam_bao_thu_muc_ghi_duoc(string $duongDan): bool
{
    if (!is_dir($duongDan) && !mkdir($duongDan, 0775, true) && !is_dir($duongDan)) {
        in_dong('[FAIL] Cannot create directory: ' . $duongDan);
        return false;
    }

    if (!is_writable($duongDan)) {
        in_dong('[FAIL] Directory is not writable: ' . $duongDan);
        return false;
    }

    in_dong('[OK] Writable: ' . $duongDan);
    return true;
}

function tao_env_neu_can(): void
{
    $env = CAI_DAT_GOC . '/.env';
    $example = CAI_DAT_GOC . '/.env.example';

    if (is_file($env)) {
        in_dong('[SKIP] .env already exists');
        return;
    }

    if (is_file($example)) {
        copy($example, $env);
        in_dong('[OK] Created .env from .env.example');
        return;
    }

    file_put_contents($env, noi_dung_mau_env());
    in_dong('[OK] Created .env from built-in template');
}

function huong_dan_import_sql(): void
{
    $seed = CAI_DAT_GOC . '/co_so_du_lieu/seed.sql';
    if (!is_file($seed)) {
        in_dong('[WARN] Missing seed file: co_so_du_lieu/seed.sql');
        return;
    }

    in_dong('[NEXT] Import sample SQL: co_so_du_lieu/seed.sql');
    in_dong('[NEXT] Default admin: admin@example.com / 123456');
}

in_dong('=== Bee Framework Installer ===');

$checks = [
    kiem_tra_php(),
    kiem_tra_pdo(),
    dam_bao_thu_muc_ghi_duoc(CAI_DAT_GOC . '/ung_dung/kho_luu/cache'),
    dam_bao_thu_muc_ghi_duoc(CAI_DAT_GOC . '/ung_dung/kho_luu/phien'),
    dam_bao_thu_muc_ghi_duoc(CAI_DAT_GOC . '/ung_dung/kho_luu/nhat_ky'),
    dam_bao_thu_muc_ghi_duoc(CAI_DAT_GOC . '/cong_khai/tai_len'),
];

if (in_array(false, $checks, true)) {
    in_dong('[STOP] Fix failed checks before continuing.');
    exit(1);
}

tao_env_neu_can();
huong_dan_import_sql();
in_dong('[DONE] Initial setup checks completed.');
