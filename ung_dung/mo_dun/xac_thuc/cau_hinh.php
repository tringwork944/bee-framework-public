<?php

use MoDun\XacThuc\DieuKhien\XacThucDieuKhien;

return [
    'ma' => 'xac_thuc',
    'ten' => 'Xac thuc',
    'mo_ta' => 'Dang nhap va quan ly phien he thong.',
    'phien_ban' => '1.0.0',
    'tac_gia' => 'Bee Framework',
    'website' => '',
    'yeu_cau_core' => '1.0.0',
    'yeu_cau_php' => '8.1',
    'kich_hoat_mac_dinh' => true,
    'loai' => 'he_thong',
    'nhom' => 'cai_dat',
    'la_mo_dun_loi' => true,
    'phu_thuoc' => [],
    'menu' => [],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/dang-nhap', 'yeu_cau_dang_nhap' => false, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'formDangNhap']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/dang-nhap', 'yeu_cau_dang_nhap' => false, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'xuLyDangNhap']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/dang-xuat', 'yeu_cau_dang_nhap' => true, 'quyen' => null, 'xu_ly' => [XacThucDieuKhien::class, 'dangXuat']],
    ],
    'quyen' => [],
    'tai_nguyen' => ['css' => [], 'js' => []],
];
