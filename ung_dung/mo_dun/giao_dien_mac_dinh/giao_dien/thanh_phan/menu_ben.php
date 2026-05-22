<?php
$menu = $GLOBALS['menu_he_thong'] ?? [];
$nguoiDung = $_SESSION['nguoi_dung'] ?? null;
$demMenu = 0;

$renderMenu = static function (array $ds) use (&$renderMenu, &$demMenu): void {
    foreach ($ds as $m) {
        $demMenu++;
        $coCon = !empty($m['con']);
        $active = !empty($m['active']);
        $id = 'menu-item-' . $demMenu;
        $iconClass = (string)($m['bieu_tuong'] ?? 'ti ti-circle');
        if ($coCon) {
            ?>
            <li class="nav-item<?= $active ? ' active' : '' ?>">
                <a class="nav-link dropdown-toggle" href="#<?= bao_mat_chuoi($id) ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?= $active ? 'true' : 'false' ?>" aria-controls="<?= bao_mat_chuoi($id) ?>">
                    <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="<?= bao_mat_chuoi($iconClass) ?>"></i></span>
                    <span class="nav-link-title"><?= bao_mat_chuoi((string)$m['tieu_de']) ?></span>
                </a>
                <div class="collapse<?= $active ? ' show' : '' ?>" id="<?= bao_mat_chuoi($id) ?>">
                    <ul class="nav nav-pills flex-column ms-4 mt-1">
                        <?php foreach (($m['con'] ?? []) as $con): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= !empty($con['active']) ? ' active' : '' ?>" href="<?= bao_mat_chuoi((string)$con['duong_dan']) ?>">
                                    <?= bao_mat_chuoi((string)$con['tieu_de']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
            <?php
            continue;
        }
        ?>
        <li class="nav-item<?= $active ? ' active' : '' ?>">
            <a class="nav-link" href="<?= bao_mat_chuoi((string)$m['duong_dan']) ?>">
                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="<?= bao_mat_chuoi($iconClass) ?>"></i></span>
                <span class="nav-link-title"><?= bao_mat_chuoi((string)$m['tieu_de']) ?></span>
            </a>
        </li>
        <?php
    }
};
?>
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="/">Bee Frame</a>
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
            <?php if (!empty($nguoiDung)): ?>
                <div class="nav-item">
                    <span class="nav-link px-2">
                        <i class="ti ti-user me-1"></i><?= bao_mat_chuoi((string)$nguoiDung['ho_ten']) ?>
                        <?php if (!empty($nguoiDung['email'])): ?>
                            <span class="text-secondary ms-1">(<?= bao_mat_chuoi((string)$nguoiDung['email']) ?>)</span>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <?php $renderMenu($menu); ?>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="/dang-xuat">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-logout"></i></span>
                        <span class="nav-link-title">Dang xuat</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
