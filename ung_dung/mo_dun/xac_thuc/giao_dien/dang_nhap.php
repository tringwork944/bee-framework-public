<div class="text-center mb-4">
    <a href="/" class="navbar-brand navbar-brand-autodark fs-2">Bee Frame</a>
    <div class="text-secondary mt-2"><i class="ti ti-lock"></i></div>
</div>

<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Dang nhap he thong</h2>

        <?php if (!empty($_SESSION['_thong_bao'])): ?>
            <?php $tb = $_SESSION['_thong_bao']; unset($_SESSION['_thong_bao']); ?>
            <div class="alert alert-<?= bao_mat_chuoi((string)($tb['loai'] ?? 'info')) ?> mb-3" role="alert">
                <?= bao_mat_chuoi((string)($tb['noi_dung'] ?? '')) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($loi)): ?>
            <div class="alert alert-danger" role="alert"><?= bao_mat_chuoi((string)$loi) ?></div>
        <?php endif; ?>

        <form method="post" action="/dang-nhap" autocomplete="off">
            <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Mat khau</label>
                <input type="password" name="mat_khau" class="form-control" required>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Dang nhap</button>
            </div>
        </form>
    </div>
</div>
