<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= bao_mat_chuoi($GLOBALS['tieu_de_trang'] ?? 'Bee Frame') ?></title>
    <link rel="stylesheet" href="<?= bao_mat_chuoi(url_tai_nguyen('dist/css/tabler.css')) ?>">
    <link rel="stylesheet" href="<?= bao_mat_chuoi(url_tai_nguyen('dist/webfont/tabler-icons.css')) ?>">
    <?php foreach (($GLOBALS['tai_nguyen_mo_dun']['css'] ?? []) as $css): ?>
        <link rel="stylesheet" href="<?= bao_mat_chuoi(url_tai_nguyen(ltrim((string)$css, '/'))) ?>">
    <?php endforeach; ?>
    <style>
        .noi-dung-day-du .card,
        .noi-dung-day-du .table-responsive,
        .noi-dung-day-du form {
            width: 100%;
        }
    </style>
</head>
