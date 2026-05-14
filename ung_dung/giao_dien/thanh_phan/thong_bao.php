<?php if (!empty($_SESSION['_thong_bao'])): ?>
    <?php $tb = $_SESSION['_thong_bao']; unset($_SESSION['_thong_bao']); ?>
    <div class="alert alert-<?= bao_mat_chuoi((string)($tb['loai'] ?? 'info')) ?> mb-3" role="alert">
        <?= bao_mat_chuoi((string)($tb['noi_dung'] ?? '')) ?>
    </div>
<?php endif; ?>
