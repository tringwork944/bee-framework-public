<?php $tepNoiDung = $noiDung ?? $noi_dung ?? null; ?>
<?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/dau_trang.php'; ?>
<body class="d-flex flex-column">
<div class="page page-center min-vh-100">
    <div class="container container-tight py-4">
        <?php if (is_string($tepNoiDung) && $tepNoiDung !== '') { require $tepNoiDung; } ?>
    </div>
</div>
<?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/chan_trang.php'; ?>
