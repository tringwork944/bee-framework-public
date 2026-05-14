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
            <div class="col-md-6"><strong>Kich hoat:</strong> <?= !empty($moDun['kich_hoat']) ? 'Dang bat' : 'Dang tat' ?></div>
            <div class="col-md-6"><strong>Anh huong menu:</strong> <?= !empty($moDun['anh_huong_menu']) ? 'Co' : 'Khong' ?></div>
            <div class="col-md-4"><strong>So route:</strong> <?= (int)$moDun['so_route'] ?></div>
            <div class="col-md-4"><strong>So menu:</strong> <?= (int)$moDun['so_menu'] ?></div>
            <div class="col-md-4"><strong>So quyen:</strong> <?= (int)$moDun['so_quyen'] ?></div>
            <div class="col-12"><strong>Duong dan:</strong> <code><?= bao_mat_chuoi((string)$moDun['duong_dan']) ?></code></div>
        </div>
    </div>
</div>
