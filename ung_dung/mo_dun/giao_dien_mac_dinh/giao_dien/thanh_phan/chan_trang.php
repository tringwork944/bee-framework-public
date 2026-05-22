<script src="<?= bao_mat_chuoi(url_tai_nguyen('dist/js/tabler.js')) ?>"></script>
<?php foreach (($GLOBALS['tai_nguyen_mo_dun']['js'] ?? []) as $js): ?>
    <script src="<?= bao_mat_chuoi(url_tai_nguyen(ltrim((string)$js, '/'))) ?>"></script>
<?php endforeach; ?>
</body>
</html>
