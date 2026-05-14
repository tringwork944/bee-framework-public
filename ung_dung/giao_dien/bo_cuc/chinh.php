<?php
$tepNoiDung = $noiDung ?? $noi_dung ?? null;
if (!is_string($tepNoiDung) || $tepNoiDung === '') {
    http_response_code(500);
    $tepNoiDung = null;
}
?>
<?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/dau_trang.php'; ?>
<body>
<div class="page">
    <?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/menu_ben.php'; ?>

    <div class="page-wrapper">
        <?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/breadcrumb.php'; ?>
        <div class="page-body">
            <div class="container-fluid px-3 px-md-4 noi-dung-day-du">
                <?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/thong_bao.php'; ?>
                <?php if ($tepNoiDung !== null) { require $tepNoiDung; } ?>
            </div>
        </div>
    </div>
</div>
<?php require GOC_DU_AN . '/ung_dung/giao_dien/thanh_phan/chan_trang.php'; ?>
