<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dang nhap | Bee Framework</title>
    <link rel="icon" type="image/svg+xml" href="/cong_khai/tai_nguyen/thuong_hieu/logo-placeholder.svg">
    <link rel="stylesheet" href="/cong_khai/dist/vendor/tabler/tabler.css">
</head>
<body class=" d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Dang nhap he thong</h2>
                <?php if (!empty($loi)): ?><div class="alert alert-danger"><?= bao_mat_chuoi($loi) ?></div><?php endif; ?>
                <form method="post" action="/dang-nhap">
                    <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mat khau</label>
                        <input type="password" name="mat_khau" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Dang nhap</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/cong_khai/dist/vendor/tabler/tabler.js"></script>
</body>
</html>
