<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chi tiet mo dun: <?= bao_mat_chuoi((string)$moDun['ma']) ?></h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><strong>Ma:</strong> <code><?= bao_mat_chuoi((string)$moDun['ma']) ?></code></div>
            <div class="col-md-6"><strong>Ten:</strong> <?= bao_mat_chuoi((string)$moDun['ten']) ?></div>
            <div class="col-md-6"><strong>Phien ban:</strong> <?= bao_mat_chuoi((string)($moDun['phien_ban'] ?: 'N/A')) ?></div>
            <div class="col-md-6"><strong>Tac gia:</strong> <?= bao_mat_chuoi((string)($moDun['tac_gia'] ?: 'N/A')) ?></div>
            <div class="col-12"><strong>Mo ta:</strong> <?= bao_mat_chuoi((string)($moDun['mo_ta'] ?: 'Khong co')) ?></div>
            <div class="col-md-6"><strong>Trang thai:</strong> <?= bao_mat_chuoi((string)$moDun['trang_thai']) ?></div>
            <div class="col-md-6"><strong>Mo dun loi:</strong> <?= !empty($moDun['la_mo_dun_loi']) ? 'Co' : 'Khong' ?></div>
            <div class="col-md-6"><strong>Loai:</strong> <?= bao_mat_chuoi((string)($moDun['loai'] ?? 'nghiep_vu')) ?></div>
            <div class="col-md-6"><strong>Yeu cau PHP:</strong> <?= bao_mat_chuoi((string)($moDun['yeu_cau_php'] ?: 'N/A')) ?></div>
            <div class="col-md-6"><strong>Yeu cau Core:</strong> <?= bao_mat_chuoi((string)($moDun['yeu_cau_core'] ?: 'N/A')) ?></div>
            <div class="col-md-6"><strong>Phu thuoc:</strong> <?= bao_mat_chuoi(implode(', ', (array)$moDun['phu_thuoc'])) ?></div>
            <div class="col-md-6"><strong>Legacy:</strong> <?= !empty($moDun['legacy']) ? 'Co' : 'Khong' ?></div>
            <div class="col-12"><strong>Loi:</strong> <?= bao_mat_chuoi((string)($moDun['loi'] ?: 'Khong co')) ?></div>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="/quan-ly-mo-dun">Quay lai</a>
        <a class="btn btn-primary" href="/quan-ly-mo-dun/kiem-tra/<?= bao_mat_chuoi((string)$moDun['ma']) ?>">Kiem tra</a>
    </div>
</div>
