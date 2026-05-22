<?php

use MoDun\TaiKhoan\DieuKhien\TaiKhoanDieuKhien;

return [
    'ma' => 'tai_khoan',
    'ten' => 'Tai khoan',
    'mo_ta' => 'Quan ly tai khoan, vai tro, trang thai va bao mat.',
    'phien_ban' => '1.0.0',
    'tac_gia' => 'Bee Framework',
    'website' => '',
    'yeu_cau_core' => '1.0.0',
    'yeu_cau_php' => '8.1',
    'kich_hoat_mac_dinh' => true,
    'loai' => 'he_thong',
    'nhom' => 'cai_dat',
    'la_mo_dun_loi' => true,
    'phu_thuoc' => ['xac_thuc'],
    'menu' => [
        [
            'ma' => 'tai_khoan',
            'tieu_de' => 'Tai khoan',
            'bieu_tuong' => 'ti ti-users',
            'duong_dan' => '/tai-khoan',
            'quyen' => 'tai_khoan.xem',
            'nhom' => 'cai_dat',
            'thu_tu' => 10,
            'hien_thi' => true,
            'con' => [],
        ]
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tai-khoan', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.xem', 'xu_ly' => [TaiKhoanDieuKhien::class, 'danhSach']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tai-khoan/them', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.them', 'xu_ly' => [TaiKhoanDieuKhien::class, 'formThem']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/tai-khoan/luu', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.them', 'xu_ly' => [TaiKhoanDieuKhien::class, 'luuMoi']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tai-khoan/sua/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.sua', 'xu_ly' => [TaiKhoanDieuKhien::class, 'formSua']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/tai-khoan/cap-nhat/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.sua', 'xu_ly' => [TaiKhoanDieuKhien::class, 'luuSua']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/tai-khoan/xoa/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.xoa', 'xu_ly' => [TaiKhoanDieuKhien::class, 'xoa']],
    ],
    'quyen' => [
        'tai_khoan.xem',
        'tai_khoan.them',
        'tai_khoan.sua',
        'tai_khoan.xoa',
    ],
    'tai_nguyen' => ['css' => [], 'js' => []],
];
