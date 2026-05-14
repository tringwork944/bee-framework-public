<?php

use MoDun\TaiKhoan\DieuKhien\TaiKhoanDieuKhien;

return [
    'ma' => 'tai_khoan',
    'ten' => 'Tai khoan',
    'kich_hoat' => true,
    'anh_huong_menu' => true,
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
            'con' => []
        ]
    ],
    'giao_dien' => [
        'layout' => 'chinh',
        'tieu_de' => 'Tai khoan',
        'breadcrumb' => [
            ['tieu_de' => 'Tong quan', 'duong_dan' => '/tong-quan'],
            ['tieu_de' => 'Tai khoan', 'duong_dan' => '/tai-khoan'],
        ],
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tai-khoan', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.xem', 'xu_ly' => [TaiKhoanDieuKhien::class, 'danhSach']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/tai-khoan/sua/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.sua', 'xu_ly' => [TaiKhoanDieuKhien::class, 'formSua']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/tai-khoan/cap-nhat/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'tai_khoan.sua', 'xu_ly' => [TaiKhoanDieuKhien::class, 'luuSua']],
    ],
    'quyen' => ['tai_khoan.xem', 'tai_khoan.sua'],
];
