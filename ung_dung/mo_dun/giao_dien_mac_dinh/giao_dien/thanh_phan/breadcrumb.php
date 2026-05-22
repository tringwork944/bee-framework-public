<?php $breadcrumb = $GLOBALS['breadcrumb'] ?? []; ?>
<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-2 align-items-center w-100">
            <div class="col">
                <div class="page-pretitle">Quan tri</div>
                <h2 class="page-title mb-0"><?= bao_mat_chuoi($GLOBALS['tieu_de_trang'] ?? 'Tong quan') ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($breadcrumb as $i => $item): ?>
                        <?php $laCuoi = $i === (count($breadcrumb) - 1); ?>
                        <li class="breadcrumb-item<?= $laCuoi ? ' active' : '' ?>">
                            <?php if (!$laCuoi && !empty($item['duong_dan'])): ?>
                                <a href="<?= bao_mat_chuoi((string)$item['duong_dan']) ?>"><?= bao_mat_chuoi((string)$item['tieu_de']) ?></a>
                            <?php else: ?>
                                <?= bao_mat_chuoi((string)$item['tieu_de']) ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</header>
