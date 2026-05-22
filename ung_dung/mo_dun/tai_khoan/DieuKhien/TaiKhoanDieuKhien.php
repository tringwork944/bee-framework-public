<?php
declare(strict_types=1);

namespace MoDun\TaiKhoan\DieuKhien;

use HeThong\YeuCau;
use MoDun\TaiKhoan\MoHinh\TaiKhoanMoHinh;

class TaiKhoanDieuKhien
{
    public static function danhSach(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $moHinh = new TaiKhoanMoHinh();
        $ds = $moHinh->layDanhSach();
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tai_khoan/GiaoDien/danh_sach.php', ['ds' => $ds]);
    }

    public static function formSua(YeuCau $yeuCau, array $thamSo): void
    {
        $nguoiDung = $_SESSION['nguoi_dung'] ?? null;
        if (!$nguoiDung) chuyen_huong('/dang-nhap');

        $moHinh = new TaiKhoanMoHinh();
        $id = (int)$thamSo['id'];
        $taiKhoan = $moHinh->timTheoId($id);
        if (!$taiKhoan) {
            http_response_code(404);
            echo 'Khong tim thay tai khoan';
            return;
        }
        $laAdmin = (($nguoiDung['ma_vai_tro'] ?? '') === 'admin');
        if (!$laAdmin && (int)$nguoiDung['id'] !== $id) {
            http_response_code(403);
            echo 'Ban khong duoc phep sua tai khoan nay.';
            return;
        }

        $vaiTro = $moHinh->layDanhSachVaiTro();
        $GLOBALS['tieu_de_trang'] = 'Sua tai khoan';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tai_khoan/GiaoDien/sua.php', [
            'taiKhoan' => $taiKhoan,
            'vaiTro' => $vaiTro,
            'loiTheoTruong' => [],
            'laAdmin' => $laAdmin,
        ]);
    }

    public static function formThem(?YeuCau $yeuCau = null, array $thamSo = []): void
    {
        $moHinh = new TaiKhoanMoHinh();
        $GLOBALS['tieu_de_trang'] = 'Them tai khoan';
        hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tai_khoan/GiaoDien/them.php', [
            'vaiTro' => $moHinh->layDanhSachVaiTro(),
            'duLieu' => [],
            'loiTheoTruong' => [],
        ]);
    }

    public static function luuMoi(YeuCau $yeuCau, array $thamSo): void
    {
        $moHinh = new TaiKhoanMoHinh();
        $hoTen = trim((string)$yeuCau->dauVao('ho_ten'));
        $email = strtolower(trim((string)$yeuCau->dauVao('email')));
        $matKhau = (string)$yeuCau->dauVao('mat_khau');
        $vaiTroId = (int)$yeuCau->dauVao('vai_tro_id', 1);
        $trangThai = (int)$yeuCau->dauVao('trang_thai', 1);
        $loi = [];

        if ($hoTen === '') $loi['ho_ten'] = 'Ho ten khong duoc de trong.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $loi['email'] = 'Email khong hop le.';
        if ($email !== '' && $moHinh->emailDaTonTai($email)) $loi['email'] = 'Email da ton tai.';
        if (strlen($matKhau) < 8) $loi['mat_khau'] = 'Mat khau toi thieu 8 ky tu.';

        if ($loi !== []) {
            $GLOBALS['tieu_de_trang'] = 'Them tai khoan';
            hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tai_khoan/GiaoDien/them.php', [
                'vaiTro' => $moHinh->layDanhSachVaiTro(),
                'duLieu' => compact('hoTen', 'email', 'vaiTroId', 'trangThai'),
                'loiTheoTruong' => $loi,
            ]);
            return;
        }

        $moHinh->taoTaiKhoan([
            'ho_ten' => $hoTen,
            'email' => $email,
            'mat_khau' => password_hash($matKhau, PASSWORD_DEFAULT),
            'vai_tro_id' => $vaiTroId,
            'trang_thai' => $trangThai,
        ]);
        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da tao tai khoan.'];
        chuyen_huong('/tai-khoan');
    }

    public static function luuSua(YeuCau $yeuCau, array $thamSo): void
    {
        $nguoiDung = $_SESSION['nguoi_dung'] ?? null;
        if (!$nguoiDung) chuyen_huong('/dang-nhap');

        $moHinh = new TaiKhoanMoHinh();
        $id = (int)$thamSo['id'];
        $taiKhoan = $moHinh->timTheoId($id, true);
        if (!$taiKhoan) {
            http_response_code(404);
            echo 'Khong tim thay tai khoan';
            return;
        }

        $laAdmin = (($nguoiDung['ma_vai_tro'] ?? '') === 'admin');
        if (!$laAdmin && (int)$nguoiDung['id'] !== $id) {
            http_response_code(403);
            echo 'Ban khong duoc phep sua tai khoan nay.';
            return;
        }

        $vaiTro = $moHinh->layDanhSachVaiTro();
        $vaiTroHopLe = array_map(static fn($v) => (int)$v['id'], $vaiTro);

        $hoTen = trim((string)$yeuCau->dauVao('ho_ten'));
        $email = strtolower(trim((string)$yeuCau->dauVao('email')));
        $soDienThoai = trim((string)$yeuCau->dauVao('so_dien_thoai', ''));
        $ghiChu = trim((string)$yeuCau->dauVao('ghi_chu', ''));
        $vaiTroId = (int)$yeuCau->dauVao('vai_tro_id', (int)$taiKhoan['vai_tro_id']);
        $trangThai = (int)$yeuCau->dauVao('trang_thai', (int)$taiKhoan['trang_thai']);
        $matKhauMoi = (string)$yeuCau->dauVao('mat_khau_moi', '');
        $xacNhanMatKhauMoi = (string)$yeuCau->dauVao('xac_nhan_mat_khau_moi', '');
        $loi = [];

        if ($hoTen === '') $loi['ho_ten'] = 'Ho ten khong duoc de trong.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $loi['email'] = 'Email khong hop le.';
        if ($email !== '' && $moHinh->emailDaTonTaiKhacId($email, $id)) $loi['email'] = 'Email da ton tai.';
        if ($soDienThoai !== '' && !preg_match('/^[0-9+\\-\\s().]{8,30}$/', $soDienThoai)) $loi['so_dien_thoai'] = 'So dien thoai khong hop le.';

        if ($laAdmin) {
            if (!in_array($vaiTroId, $vaiTroHopLe, true)) $loi['vai_tro_id'] = 'Vai tro khong hop le.';
            if (!in_array($trangThai, [0, 1], true)) $loi['trang_thai'] = 'Trang thai khong hop le.';
        } else {
            $vaiTroId = (int)$taiKhoan['vai_tro_id'];
            $trangThai = (int)$taiKhoan['trang_thai'];
        }

        if ($matKhauMoi !== '' || $xacNhanMatKhauMoi !== '') {
            if (strlen($matKhauMoi) < 8) $loi['mat_khau_moi'] = 'Mat khau moi phai tu 8 ky tu.';
            if ($matKhauMoi !== $xacNhanMatKhauMoi) $loi['xac_nhan_mat_khau_moi'] = 'Nhap lai mat khau khong khop.';
        }

        $duLieuForm = $taiKhoan;
        $duLieuForm['ho_ten'] = $hoTen;
        $duLieuForm['email'] = $email;
        $duLieuForm['vai_tro_id'] = $vaiTroId;
        $duLieuForm['trang_thai'] = $trangThai;
        if ($moHinh->coCot('so_dien_thoai')) $duLieuForm['so_dien_thoai'] = $soDienThoai;
        if ($moHinh->coCot('ghi_chu')) $duLieuForm['ghi_chu'] = $ghiChu;

        if ($loi !== []) {
            $GLOBALS['tieu_de_trang'] = 'Sua tai khoan';
            hien_thi_bo_cuc(GOC_DU_AN . '/ung_dung/mo_dun/tai_khoan/GiaoDien/sua.php', [
                'taiKhoan' => $duLieuForm,
                'vaiTro' => $vaiTro,
                'loiTheoTruong' => $loi,
                'laAdmin' => $laAdmin,
            ]);
            return;
        }

        $moHinh->capNhatTaiKhoanDayDu($id, [
            'ho_ten' => $hoTen,
            'email' => $email,
            'vai_tro_id' => $vaiTroId,
            'trang_thai' => $trangThai,
            'so_dien_thoai' => $soDienThoai !== '' ? $soDienThoai : null,
            'ghi_chu' => $ghiChu !== '' ? $ghiChu : null,
            'mat_khau_moi_hash' => $matKhauMoi !== '' ? password_hash($matKhauMoi, PASSWORD_DEFAULT) : null,
        ]);

        if ((int)$nguoiDung['id'] === $id) {
            $moi = $moHinh->timTheoId($id);
            if ($moi) {
                $_SESSION['nguoi_dung']['ho_ten'] = $moi['ho_ten'];
                $_SESSION['nguoi_dung']['email'] = $moi['email'];
                $_SESSION['nguoi_dung']['vai_tro_id'] = $moi['vai_tro_id'];
                if (array_key_exists('so_dien_thoai', $moi)) $_SESSION['nguoi_dung']['so_dien_thoai'] = $moi['so_dien_thoai'];
            }
        }

        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da cap nhat tai khoan.'];
        chuyen_huong('/tai-khoan/sua/' . $id);
    }

    public static function xoa(YeuCau $yeuCau, array $thamSo): void
    {
        $id = (int)($thamSo['id'] ?? 0);
        $nguoiDung = $_SESSION['nguoi_dung'] ?? null;
        if ($id <= 0 || !$nguoiDung || (int)$nguoiDung['id'] === $id) {
            $_SESSION['_thong_bao'] = ['loai' => 'danger', 'noi_dung' => 'Khong the xoa tai khoan nay.'];
            chuyen_huong('/tai-khoan');
        }
        $moHinh = new TaiKhoanMoHinh();
        $moHinh->xoaTaiKhoan($id);
        $_SESSION['_thong_bao'] = ['loai' => 'success', 'noi_dung' => 'Da xoa tai khoan.'];
        chuyen_huong('/tai-khoan');
    }
}
