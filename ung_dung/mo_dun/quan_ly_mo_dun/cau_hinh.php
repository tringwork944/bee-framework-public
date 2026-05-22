<?php

use MoDun\QuanLyMoDun\DieuKhien\QuanLyMoDunDieuKhien;

return [
    'ma' => 'quan_ly_mo_dun',
    'ten' => 'Quan ly mo dun',
    'mo_ta' => 'Quan ly vong doi mo dun: cai dat, kich hoat, tat, go cai dat.',
    'phien_ban' => '1.0.1',
    'tac_gia' => 'Bee Framework',
    'website' => '',
    'kich_hoat_mac_dinh' => true,
    'yeu_cau_core' => '1.0.0',
    'yeu_cau_php' => '8.1',
    'loai' => 'he_thong',
    'nhom' => 'cai_dat',
    'la_mo_dun_loi' => true,
    'phu_thuoc' => ['xac_thuc', 'tai_khoan'],
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
    'tai_nguyen' => ['css' => [], 'js' => []],
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
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun/tai-len', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.tai_len', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'hienThiTaiLen']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/tai-len', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.tai_len', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'xuLyTaiLen']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun/chi-tiet/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.xem', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'chiTiet']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/cai-dat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.cai_dat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'caiDat']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/kich-hoat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.kich_hoat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'kichHoat']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/tat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.tat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'tat']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/go-cai-dat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.go_cai_dat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'goCaiDat']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/quan-ly-mo-dun/kiem-tra/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.kiem_tra', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'kiemTra']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/quan-ly-mo-dun/bat-tat/{ma}', 'yeu_cau_dang_nhap' => true, 'quyen' => 'quan_ly_mo_dun.kich_hoat', 'xu_ly' => [QuanLyMoDunDieuKhien::class, 'batTatLegacy']],
    ],
    'quyen' => [
        'quan_ly_mo_dun.xem',
        'quan_ly_mo_dun.cai_dat',
        'quan_ly_mo_dun.kich_hoat',
        'quan_ly_mo_dun.tat',
        'quan_ly_mo_dun.go_cai_dat',
        'quan_ly_mo_dun.kiem_tra',
        'quan_ly_mo_dun.tai_len',
    ],
];
