<?php

use MoDun\TongQuan\DieuKhien\TongQuanDieuKhien;

return [
    'ma' => 'tong_quan',
    'ten' => 'Tong quan',
    'kich_hoat' => true,
    'anh_huong_menu' => true,
    'menu' => [
        [
            'ma' => 'tong_quan',
            'tieu_de' => 'Tong quan',
            'bieu_tuong' => 'ti ti-layout-dashboard',
            'duong_dan' => '/tong-quan',
            'quyen' => 'tong_quan.xem',
            'thu_tu' => 1,
            'hien_thi' => true,
            'con' => []
        ]
    ],
    'giao_dien' => [
        'layout' => 'chinh',
        'tieu_de' => 'Tong quan',
        'breadcrumb' => [
            ['tieu_de' => 'Tong quan', 'duong_dan' => '/tong-quan'],
        ],
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tong_quan.xem', 'xu_ly' => [TongQuanDieuKhien::class, 'index']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tong-quan', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tong_quan.xem', 'xu_ly' => [TongQuanDieuKhien::class, 'index']],
    ],
    'quyen' => ['tong_quan.xem'],
];
