<?php

use MoDun\QuanLyMoDun\DieuKhien\QuanLyMoDunDieuKhien;

return [
    'ma' => 'quan_ly_mo_dun',
    'ten' => 'Quan ly mo dun',
    'phien_ban' => '1.0.0',
    'tac_gia' => 'Bee Framework',
    'mo_ta' => 'Quan ly danh sach mo dun, bat/tat va kiem tra cau truc.',
    'kich_hoat' => true,
    'anh_huong_menu' => true,
    'menu' => [
        [
            'ma' => 'quan_ly_mo_dun',
            'tieu_de' => 'Mo dun',
            'bieu_tuong' => 'ti ti-puzzle',
            'duong_dan' => '/quan-ly-mo-dun',
            'quyen' => 'quan_ly_mo_dun.xem',
            'nhom' => 'cai_dat',
            'thu_tu' => 90,
            'hien_thi' => true,
            'con' => []
        ]
    ],
    'giao_dien' => [
        'layout' => 'chinh',
        'tieu_de' => 'Quan ly mo dun',
        'breadcrumb' => [
            ['tieu_de' => 'Tong quan', 'duong_dan' => '/tong-quan'],
            ['tieu_de' => 'Quan ly mo dun', 'duong_dan' => '/quan-ly-mo-dun'],
        ],
    ],
    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.xem', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'danhSach']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun/chi-tiet/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.chi_tiet', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'chiTiet']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/bat-tat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.bat_tat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'batTat']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun/kiem-tra/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.kiem_tra', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'kiemTra']],
    ],
    'quyen' => [
        'quan_ly_mo_dun.xem',
        'quan_ly_mo_dun.chi_tiet',
        'quan_ly_mo_dun.bat_tat',
        'quan_ly_mo_dun.kiem_tra',
    ],
];
