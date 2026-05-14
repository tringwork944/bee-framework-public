<?php
$loiTheoTruong = $loiTheoTruong ?? [];
$laAdmin = $laAdmin ?? false;
?>
<form method="post" action="/tai-khoan/cap-nhat/<?= (int)$taiKhoan['id'] ?>" novalidate>
    <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">

    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title mb-1">Sua tai khoan #<?= (int)$taiKhoan['id'] ?></h3>
                <p class="text-secondary mb-0">Cap nhat thong tin, bao mat va trang thai tai khoan</p>
            </div>
        </div>

        <div class="card-body">
            <h4 class="mb-3">Thong tin tai khoan</h4>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Ho ten</label>
                    <input class="form-control<?= isset($loiTheoTruong['ho_ten']) ? ' is-invalid' : '' ?>" name="ho_ten" value="<?= bao_mat_chuoi((string)($taiKhoan['ho_ten'] ?? '')) ?>" required>
                    <?php if (isset($loiTheoTruong['ho_ten'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['ho_ten']) ?></div><?php endif; ?>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control<?= isset($loiTheoTruong['email']) ? ' is-invalid' : '' ?>" name="email" value="<?= bao_mat_chuoi((string)($taiKhoan['email'] ?? '')) ?>" required>
                    <?php if (isset($loiTheoTruong['email'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['email']) ?></div><?php endif; ?>
                </div>

                <?php if (array_key_exists('so_dien_thoai', $taiKhoan)): ?>
                <div class="col-12 col-md-6">
                    <label class="form-label">So dien thoai</label>
                    <input class="form-control<?= isset($loiTheoTruong['so_dien_thoai']) ? ' is-invalid' : '' ?>" name="so_dien_thoai" value="<?= bao_mat_chuoi((string)($taiKhoan['so_dien_thoai'] ?? '')) ?>">
                    <?php if (isset($loiTheoTruong['so_dien_thoai'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['so_dien_thoai']) ?></div><?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

            <hr class="my-4">

            <h4 class="mb-3">Bao mat</h4>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Mat khau moi</label>
                    <input type="password" class="form-control<?= isset($loiTheoTruong['mat_khau_moi']) ? ' is-invalid' : '' ?>" name="mat_khau_moi">
                    <?php if (isset($loiTheoTruong['mat_khau_moi'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['mat_khau_moi']) ?></div><?php endif; ?>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Nhap lai mat khau moi</label>
                    <input type="password" class="form-control<?= isset($loiTheoTruong['xac_nhan_mat_khau_moi']) ? ' is-invalid' : '' ?>" name="xac_nhan_mat_khau_moi">
                    <?php if (isset($loiTheoTruong['xac_nhan_mat_khau_moi'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['xac_nhan_mat_khau_moi']) ?></div><?php endif; ?>
                </div>
                <div class="col-12">
                    <div class="text-secondary">De trong neu khong muon doi mat khau</div>
                </div>
            </div>

            <hr class="my-4">

            <h4 class="mb-3">Vai tro va trang thai</h4>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Vai tro</label>
                    <select class="form-select<?= isset($loiTheoTruong['vai_tro_id']) ? ' is-invalid' : '' ?>" name="vai_tro_id" <?= $laAdmin ? '' : 'disabled' ?>>
                        <?php foreach ($vaiTro as $vt): ?>
                            <option value="<?= (int)$vt['id'] ?>" <?= (int)$taiKhoan['vai_tro_id'] === (int)$vt['id'] ? 'selected' : '' ?>><?= bao_mat_chuoi((string)$vt['ten']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!$laAdmin): ?><div class="text-secondary mt-1">Ban khong co quyen thay doi vai tro.</div><?php endif; ?>
                    <?php if (isset($loiTheoTruong['vai_tro_id'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['vai_tro_id']) ?></div><?php endif; ?>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Trang thai</label>
                    <select class="form-select<?= isset($loiTheoTruong['trang_thai']) ? ' is-invalid' : '' ?>" name="trang_thai" <?= $laAdmin ? '' : 'disabled' ?>>
                        <option value="1" <?= (int)$taiKhoan['trang_thai'] === 1 ? 'selected' : '' ?>>Kich hoat</option>
                        <option value="0" <?= (int)$taiKhoan['trang_thai'] === 0 ? 'selected' : '' ?>>Khoa</option>
                    </select>
                    <?php if (!$laAdmin): ?><div class="text-secondary mt-1">Ban khong co quyen thay doi trang thai.</div><?php endif; ?>
                    <?php if (isset($loiTheoTruong['trang_thai'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['trang_thai']) ?></div><?php endif; ?>
                </div>

                <?php if (array_key_exists('ghi_chu', $taiKhoan)): ?>
                <div class="col-12">
                    <label class="form-label">Ghi chu</label>
                    <textarea class="form-control<?= isset($loiTheoTruong['ghi_chu']) ? ' is-invalid' : '' ?>" name="ghi_chu" rows="3"><?= bao_mat_chuoi((string)($taiKhoan['ghi_chu'] ?? '')) ?></textarea>
                    <?php if (isset($loiTheoTruong['ghi_chu'])): ?><div class="invalid-feedback"><?= bao_mat_chuoi($loiTheoTruong['ghi_chu']) ?></div><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">Luu thay doi</button>
            <a class="btn btn-outline-secondary" href="/tai-khoan">Quay lai</a>
        </div>
    </div>
</form>
