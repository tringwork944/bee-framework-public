<?php
$tepNoiDung = $noiDung ?? $noi_dung ?? null;
if (!is_string($tepNoiDung) || $tepNoiDung === '') {
    http_response_code(500);
    $tepNoiDung = null;
}
?>
<?php render_partial('dau_trang'); ?>
<body>
<div class="page">
    <?php render_partial('menu_ben'); ?>

    <div class="page-wrapper">
        <?php render_partial('breadcrumb'); ?>
        <div class="page-body">
            <div class="container-fluid px-3 px-md-4 noi-dung-day-du">
                <?php render_partial('thong_bao'); ?>
                <?php if ($tepNoiDung !== null) { require $tepNoiDung; } ?>
            </div>
        </div>
    </div>
</div>
<?php render_partial('chan_trang'); ?>
