<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sach mo dun</h3>
        <div class="card-actions">
            <?php if (co_quyen('quan_ly_mo_dun.tai_len')): ?>
                <a class="btn btn-primary btn-sm" href="/quan-ly-mo-dun/tai-len">
                    <i class="ti ti-upload me-1"></i>Tai mo dun
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
            <tr>
                <th>Ma</th>
                <th>Ten / Mo ta</th>
                <th>Phien ban</th>
                <th>Tac gia</th>
                <th>Trang thai</th>
                <th>Loai</th>
                <th>Yeu cau</th>
                <th>Phu thuoc</th>
                <th class="text-end">Tac vu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($danhSach as $m): ?>
                <tr>
                    <td><code><?= bao_mat_chuoi((string)$m['ma']) ?></code></td>
                    <td>
                        <div class="fw-semibold"><?= bao_mat_chuoi((string)$m['ten']) ?></div>
                        <div class="text-secondary small"><?= bao_mat_chuoi((string)$m['mo_ta']) ?></div>
                        <?php if (!empty($m['loi'])): ?><div class="text-danger small">Loi: <?= bao_mat_chuoi((string)$m['loi']) ?></div><?php endif; ?>
                        <?php if (!empty($m['legacy'])): ?><span class="badge bg-yellow-lt">Legacy</span><?php endif; ?>
                    </td>
                    <td><?= bao_mat_chuoi((string)$m['phien_ban']) ?></td>
                    <td><?= bao_mat_chuoi((string)$m['tac_gia']) ?></td>
                    <td>
                        <?php if (!empty($m['la_mo_dun_loi'])): ?><span class="badge bg-azure-lt">Mo dun loi</span><?php endif; ?>
                        <?php if ($m['trang_thai'] === 'chua_cai_dat'): ?><span class="badge bg-secondary-lt">Chua cai dat</span><?php endif; ?>
                        <?php if ($m['trang_thai'] === 'da_cai_dat'): ?><span class="badge bg-blue-lt">Da cai dat</span><?php endif; ?>
                        <?php if ($m['trang_thai'] === 'dang_bat'): ?><span class="badge bg-green-lt">Dang bat</span><?php endif; ?>
                        <?php if ($m['trang_thai'] === 'dang_tat'): ?><span class="badge bg-orange-lt">Dang tat</span><?php endif; ?>
                        <?php if ($m['trang_thai'] === 'loi'): ?><span class="badge bg-red-lt">Loi</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if (($m['loai'] ?? '') === 'giao_dien'): ?><span class="badge bg-purple-lt">Giao dien</span><?php endif; ?>
                        <?php if (($m['loai'] ?? '') === 'he_thong'): ?><span class="badge bg-blue-lt">He thong</span><?php endif; ?>
                        <?php if (($m['loai'] ?? '') === 'nghiep_vu'): ?><span class="badge bg-green-lt">Nghiep vu</span><?php endif; ?>
                    </td>
                    <td>
                        <small class="text-secondary">PHP: <?= bao_mat_chuoi((string)$m['yeu_cau_php']) ?><br>Core: <?= bao_mat_chuoi((string)$m['yeu_cau_core']) ?></small>
                    </td>
                    <td><small class="text-secondary"><?= bao_mat_chuoi(implode(', ', $m['phu_thuoc'])) ?></small></td>
                    <td class="text-end">
                        <a class="btn btn-sm" href="/quan-ly-mo-dun/chi-tiet/<?= bao_mat_chuoi((string)$m['ma']) ?>">Chi tiet</a>
                        <a class="btn btn-sm" href="/quan-ly-mo-dun/kiem-tra/<?= bao_mat_chuoi((string)$m['ma']) ?>">Kiem tra</a>
                        <?php if ($m['trang_thai'] === 'chua_cai_dat' || $m['trang_thai'] === 'loi'): ?>
                            <form method="post" action="/quan-ly-mo-dun/cai-dat/<?= bao_mat_chuoi((string)$m['ma']) ?>" class="d-inline">
                                <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                                <button class="btn btn-sm btn-primary" type="submit">Cai dat</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($m['trang_thai'] === 'da_cai_dat' || $m['trang_thai'] === 'dang_tat'): ?>
                            <form method="post" action="/quan-ly-mo-dun/kich-hoat/<?= bao_mat_chuoi((string)$m['ma']) ?>" class="d-inline">
                                <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                                <button class="btn btn-sm btn-success" type="submit">Kich hoat</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($m['trang_thai'] === 'dang_bat' && empty($m['la_mo_dun_loi'])): ?>
                            <form method="post" action="/quan-ly-mo-dun/tat/<?= bao_mat_chuoi((string)$m['ma']) ?>" class="d-inline">
                                <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                                <button class="btn btn-sm btn-warning" type="submit">Tat</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($m['trang_thai'] === 'dang_tat' && empty($m['la_mo_dun_loi']) && co_quyen('quan_ly_mo_dun.go_cai_dat')): ?>
                            <button
                                class="btn btn-sm btn-danger"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-go-cai-dat-<?= bao_mat_chuoi((string)$m['ma']) ?>"
                            >
                                <i class="ti ti-trash me-1"></i>Go cai dat
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php foreach ($danhSach as $m): ?>
    <?php if ($m['trang_thai'] !== 'dang_tat' || !empty($m['la_mo_dun_loi']) || !co_quyen('quan_ly_mo_dun.go_cai_dat')): ?>
        <?php continue; ?>
    <?php endif; ?>
    <div class="modal modal-blur fade" id="modal-go-cai-dat-<?= bao_mat_chuoi((string)$m['ma']) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="/quan-ly-mo-dun/go-cai-dat/<?= bao_mat_chuoi((string)$m['ma']) ?>">
                    <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Xac nhan go cai dat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="text-secondary small">Mo dun</div>
                            <div class="fw-semibold"><?= bao_mat_chuoi((string)$m['ten']) ?> <code><?= bao_mat_chuoi((string)$m['ma']) ?></code></div>
                        </div>
                        <div class="alert alert-danger" role="alert">
                            <div>Go cai dat se:</div>
                            <div>- Xoa menu, quyen, cau hinh va du lieu CSDL lien quan.</div>
                            <div>- Xoa toan bo thu muc ma nguon mo dun khoi may chu.</div>
                            <div>- Thao tac nay KHONG the hoan tac.</div>
                        </div>
                        <label class="form-check">
                            <input class="form-check-input js-xac-nhan-go-cai-dat" type="checkbox" name="xac_nhan_xoa_du_lieu" value="1" required>
                            <span class="form-check-label">Toi hieu thao tac nay se xoa toan bo du lieu va ma nguon mo dun</span>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Huy</button>
                        <button class="btn btn-danger" type="submit" disabled>
                            <i class="ti ti-trash me-1"></i>Xac nhan go cai dat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script>
document.addEventListener('change', function (event) {
    if (!event.target.classList.contains('js-xac-nhan-go-cai-dat')) {
        return;
    }

    var form = event.target.form;
    if (!form) {
        return;
    }

    var submit = form.querySelector('button[type="submit"]');
    if (!submit) {
        return;
    }

    submit.disabled = !event.target.checked;
});

document.addEventListener('hidden.bs.modal', function (event) {
    var modal = event.target;
    if (!modal || !modal.classList.contains('modal')) {
        return;
    }

    var checkbox = modal.querySelector('.js-xac-nhan-go-cai-dat');
    var submit = modal.querySelector('button[type="submit"]');
    if (!checkbox || !submit) {
        return;
    }

    checkbox.checked = false;
    submit.disabled = true;
});
</script>
