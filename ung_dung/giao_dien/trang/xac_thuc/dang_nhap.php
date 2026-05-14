<div class="text-center mb-4">
    <div class="mb-3"><i class="ti ti-user" style="font-size:40px;"></i></div>
    <h2 class="h2 m-0">Dang nhap he thong</h2>
</div>
<div class="card card-md">
    <div class="card-body">
        <?php if (!empty($loi)): ?>
            <div class="alert alert-danger" role="alert"><?= bao_mat_chuoi($loi) ?></div>
        <?php endif; ?>
        <form method="post" action="/dang-nhap" novalidate>
            <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autocomplete="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Mat khau</label>
                <input type="password" name="mat_khau" class="form-control" required autocomplete="current-password">
            </div>
            <button class="btn btn-primary w-100" type="submit">Dang nhap</button>
        </form>
    </div>
</div>
