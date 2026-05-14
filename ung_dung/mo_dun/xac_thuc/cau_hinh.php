<?php

use MoDun\XacThuc\DieuKhien\XacThucDieuKhien;

return [
    'ma' => 'xac_thuc',
    'ten' => 'Xac thuc',
    'kich_hoat' => true,
    'anh_huong_menu' => false,
    'menu' => null,
    'giao_dien' => [
        'layout' => 'dang_nhap',
        'tieu_de' => 'Dang nhap',
        'breadcrumb' => [],
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/dang-nhap', 'yeu_cau_dang_nhap' => false, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'formDangNhap']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/dang-nhap', 'yeu_cau_dang_nhap' => false, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'xuLyDangNhap']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/dang-xuat', 'yeu_cau_dang_nhap' => true, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'dangXuat']],
    ],
    'quyen' => [],
];
