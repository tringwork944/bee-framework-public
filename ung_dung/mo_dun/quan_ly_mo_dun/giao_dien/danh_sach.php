<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sach mo dun</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
            <tr>
                <th>Ma</th>
                <th>Ten</th>
                <th>Trang thai</th>
                <th>Thong tin</th>
                <th class="text-end">Tac vu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($danhSach as $m): ?>
                <tr>
                    <td><code><?= bao_mat_chuoi((string)$m['ma']) ?></code></td>
                    <td><?= bao_mat_chuoi((string)$m['ten']) ?></td>
                    <td>
                        <?php if (!empty($m['la_mo_dun_loi'])): ?><span class="badge bg-azure-lt">Mo dun loi</span><?php endif; ?>
                        <?php if (!empty($m['kich_hoat'])): ?><span class="badge bg-green-lt">Dang bat</span><?php else: ?><span class="badge bg-secondary-lt">Dang tat</span><?php endif; ?>
                        <?php if (empty($m['hop_le'])): ?><span class="badge bg-red-lt">Loi cau truc</span><?php endif; ?>
                    </td>
                    <td>
                        <small class="text-secondary">
                            Route: <?= (int)$m['so_route'] ?>,
                            Menu: <?= (int)$m['so_menu'] ?>,
                            Quyen: <?= (int)$m['so_quyen'] ?>
                        </small>
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm" href="/quan-ly-mo-dun/chi-tiet/<?= bao_mat_chuoi((string)$m['ma']) ?>">Chi tiet</a>
                        <a class="btn btn-sm" href="/quan-ly-mo-dun/kiem-tra/<?= bao_mat_chuoi((string)$m['ma']) ?>">Kiem tra</a>
                        <?php if (empty($m['la_mo_dun_loi'])): ?>
                            <form method="post" action="/quan-ly-mo-dun/bat-tat/<?= bao_mat_chuoi((string)$m['ma']) ?>" class="d-inline">
                                <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                                <input type="hidden" name="trang_thai" value="<?= !empty($m['kich_hoat']) ? '0' : '1' ?>">
                                <button class="btn btn-sm <?= !empty($m['kich_hoat']) ? 'btn-warning' : 'btn-success' ?>" type="submit">
                                    <?= !empty($m['kich_hoat']) ? 'Tat' : 'Bat' ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary" type="button" disabled>Khoa</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
