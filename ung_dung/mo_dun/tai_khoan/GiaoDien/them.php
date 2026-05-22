<?php
$loiTheoTruong = $loiTheoTruong ?? [];
$duLieu = $duLieu ?? [];
?>
<form method="post" action="/tai-khoan/luu">
    <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Them tai khoan</h3></div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Ho ten</label>
                <input class="form-control<?= isset($loiTheoTruong['ho_ten']) ? ' is-invalid' : '' ?>" name="ho_ten" value="<?= bao_mat_chuoi((string)($duLieu['hoTen'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control<?= isset($loiTheoTruong['email']) ? ' is-invalid' : '' ?>" name="email" value="<?= bao_mat_chuoi((string)($duLieu['email'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Mat khau</label>
                <input type="password" class="form-control<?= isset($loiTheoTruong['mat_khau']) ? ' is-invalid' : '' ?>" name="mat_khau">
            </div>
            <div class="col-md-3">
                <label class="form-label">Vai tro</label>
                <select class="form-select" name="vai_tro_id">
                    <?php foreach ($vaiTro as $vt): ?>
                        <option value="<?= (int)$vt['id'] ?>" <?= ((int)($duLieu['vaiTroId'] ?? 1) === (int)$vt['id']) ? 'selected' : '' ?>><?= bao_mat_chuoi((string)$vt['ten']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trang thai</label>
                <select class="form-select" name="trang_thai">
                    <option value="1" <?= ((int)($duLieu['trangThai'] ?? 1) === 1) ? 'selected' : '' ?>>Kich hoat</option>
                    <option value="0" <?= ((int)($duLieu['trangThai'] ?? 1) === 0) ? 'selected' : '' ?>>Khoa</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">Luu</button>
            <a class="btn btn-outline-secondary" href="/tai-khoan">Huy</a>
        </div>
    </div>
</form>
