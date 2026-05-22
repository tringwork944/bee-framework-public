<?php

use MoDun\TongQuan\DieuKhien\TongQuanDieuKhien;

return [
    'ma' => 'tong_quan',
    'ten' => 'Tong quan',
    'mo_ta' => 'Trang tong quan he thong.',
    'phien_ban' => '1.0.0',
    'tac_gia' => 'Bee Framework',
    'website' => '',
    'yeu_cau_core' => '1.0.0',
    'yeu_cau_php' => '8.1',
    'kich_hoat_mac_dinh' => true,
    'loai' => 'he_thong',
    'nhom' => 'nghiep_vu',
    'la_mo_dun_loi' => true,
    'phu_thuoc' => ['xac_thuc'],
    'menu' => [
        [
            'ma' => 'tong_quan',
            'tieu_de' => 'Tong quan',
            'bieu_tuong' => 'ti ti-dashboard',
            'duong_dan' => '/',
            'quyen' => 'tong_quan.xem',
            'nhom' => 'nghiep_vu',
            'thu_tu' => 1,
            'hien_thi' => true,
            'con' => [],
        ],
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tong_quan.xem', 'xu_ly' => [TongQuanDieuKhien::class, 'index']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tong-quan', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tong_quan.xem', 'xu_ly' => [TongQuanDieuKhien::class, 'index']],
    ],
    'quyen' => ['tong_quan.xem'],
    'tai_nguyen' => ['css' => [], 'js' => []],
];
