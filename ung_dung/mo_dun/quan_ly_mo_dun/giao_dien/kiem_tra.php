<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kiem tra cau truc mo dun: <?= bao_mat_chuoi((string)$maMoDun) ?></h3>
    </div>
    <div class="card-body">
        <?php if (!empty($moDun)): ?>
            <p class="mb-2">Ten mo dun: <strong><?= bao_mat_chuoi((string)$moDun['ten']) ?></strong></p>
        <?php endif; ?>

        <?php if (empty($ketQua['loi'])): ?>
            <div class="alert alert-success mb-0">Cau truc hop le.</div>
        <?php else: ?>
            <div class="alert alert-danger mb-2">Phat hien loi cau truc:</div>
            <ul class="mb-0">
                <?php foreach ($ketQua['loi'] as $loi): ?>
                    <li><?= bao_mat_chuoi((string)$loi) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
