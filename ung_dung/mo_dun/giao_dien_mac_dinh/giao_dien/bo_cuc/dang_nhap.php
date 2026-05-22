<?php $tepNoiDung = $noiDung ?? $noi_dung ?? null; ?>
<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dang nhap</title>
    <link rel="stylesheet" href="<?= bao_mat_chuoi(url_tai_nguyen('dist/css/tabler.css')) ?>">
    <link rel="stylesheet" href="<?= bao_mat_chuoi(url_tai_nguyen('dist/webfont/tabler-icons.css')) ?>">
    <style>
        .container-tight {
            max-width: 420px;
            width: 100%;
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>
</head>
<body class="d-flex flex-column">
<div class="page page-center min-vh-100">
    <div class="container container-tight py-4">
        <?php if (is_string($tepNoiDung) && $tepNoiDung !== '') { require $tepNoiDung; } ?>
    </div>
</div>
<script src="<?= bao_mat_chuoi(url_tai_nguyen('dist/js/tabler.js')) ?>"></script>
</body>
</html>
