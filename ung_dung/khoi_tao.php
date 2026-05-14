<?php
declare(strict_types=1);

spl_autoload_register(static function (string $lop): void {
    $goiTienTo = [
        'HeThong\\' => GOC_DU_AN . '/ung_dung/he_thong/',
        'MoDun\\' => GOC_DU_AN . '/ung_dung/mo_dun/',
    ];

    foreach ($goiTienTo as $tienTo => $thuMuc) {
        if (str_starts_with($lop, $tienTo)) {
            $tuongDoi = str_replace('\\', '/', substr($lop, strlen($tienTo)));
            $ungVien = [$thuMuc . $tuongDoi . '.php'];

            if ($tienTo === 'MoDun\\') {
                $thanhPhan = explode('/', $tuongDoi);
                if (count($thanhPhan) >= 2) {
                    $kieuCu = $thanhPhan;
                    $kieuCu[0] = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $kieuCu[0]));
                    $ungVien[] = $thuMuc . implode('/', $kieuCu) . '.php';

                    $kieuMoi = $kieuCu;
                    for ($i = 1; $i < count($kieuMoi) - 1; $i++) {
                        $kieuMoi[$i] = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $kieuMoi[$i]));
                    }
                    $ungVien[] = $thuMuc . implode('/', $kieuMoi) . '.php';
                }
            }

            foreach (array_unique($ungVien) as $tep) {
                if (is_file($tep)) {
                    require $tep;
                    return;
                }
            }
        }
    }
});

require GOC_DU_AN . '/ung_dung/he_thong/TroGiup.php';
