<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Danh sach du lieu mau</h2>
        </div>
        <div class="col-auto ms-auto">
            <a href="/mau/them" class="btn btn-primary">Them moi</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
            <tr><th>ID</th><th>Ten</th><th>Tao luc</th><th class="text-end">Tac vu</th></tr>
            </thead>
            <tbody>
            <?php foreach (($duLieu ?? []) as $item): ?>
                <tr>
                    <td><?= (int)$item['id'] ?></td>
                    <td><?= bao_mat_chuoi((string)$item['ten']) ?></td>
                    <td><?= bao_mat_chuoi((string)($item['tao_luc'] ?? '')) ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm" href="/mau/sua/<?= (int)$item['id'] ?>">Sua</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
