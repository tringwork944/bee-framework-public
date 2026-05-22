<?php
declare(strict_types=1);

namespace HeThong;

class KhoiDong
{
    public function chay(): void
    {
        Phien::batDau();

        $boNap = new BoNapMoDun();
        $boNapGiaoDien = new BoNapGiaoDien();
        $boNapGiaoDien->nap();
        $duLieuMoDun = $boNap->nap();
        $router = new BoDinhTuyen();
        $xacThuc = new XacThuc();
        $kiemTraQuyen = new KiemTraQuyen();
        $quanLyMenu = new QuanLyMenu($kiemTraQuyen);
        $quanLyTaiNguyen = new QuanLyTaiNguyen();
        $quanLyGiaoDien = new QuanLyGiaoDien($quanLyMenu, $quanLyTaiNguyen);
        $yeuCau = new YeuCau();
        $quanLyMenu->dongBoMenuTuMoDun($duLieuMoDun['tat_ca_mo_dun'] ?? []);
        $this->napBootstrapMoDunDangBat($duLieuMoDun['mo_dun'] ?? []);
        SuKien::goiHanhDong('he_thong.khoi_dong');

        foreach ($duLieuMoDun['tuyen'] as $tuyen) {
            $router->dangKy($tuyen['phuong_thuc'], $tuyen['duong_dan'],
                function (YeuCau $rq, array $thamSo, array $tuyChon) use ($tuyen, $xacThuc, $kiemTraQuyen, $duLieuMoDun, $quanLyGiaoDien) {
                    $canDangNhap = $tuyen['yeu_cau_dang_nhap'] ?? false;
                    $maQuyen = $tuyen['quyen'] ?? null;

                    if ($canDangNhap && !$xacThuc->daDangNhap()) {
                        chuyen_huong('/dang-nhap');
                    }

                    if (!$kiemTraQuyen->coQuyen($xacThuc->nguoiDung(), $maQuyen)) {
                        http_response_code(403);
                        hien_thi('loi/403.php');
                        return null;
                    }

                    if ($rq->phuongThuc() === 'POST' && !csrf_kiem_tra((string)$rq->dauVao('_csrf'))) {
                        http_response_code(419);
                        return 'CSRF token khong hop le.';
                    }

                    $quanLyGiaoDien->chuanBi($duLieuMoDun, $tuyen, $xacThuc->nguoiDung(), $rq->duongDan());
                    return call_user_func($tuyen['xu_ly'], $rq, $thamSo);
                },
                []
            );
        }

        $router->xuLy($yeuCau);
    }

    private function napBootstrapMoDunDangBat(array $moDunDangBat): void
    {
        foreach ($moDunDangBat as $ma => $cauHinh) {
            $duongDan = GOC_DU_AN . '/ung_dung/mo_dun/' . $ma . '/mo_dun.php';
            if (!is_file($duongDan)) {
                continue;
            }
            $thucThi = require $duongDan;
            if (is_callable($thucThi)) {
                $thucThi($cauHinh);
            }
        }
    }
}
