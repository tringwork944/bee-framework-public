<?php

use MoDun\Mau\DieuKhien\MauDieuKhien;

return [
    'ma' => '_mau',
    'ten' => 'Mo dun mau',
    'phien_ban' => '1.0.0',
    'tac_gia' => 'Framework',
    'mo_ta' => 'Mo dun mau de phat trien chuc nang moi',
    'kich_hoat' => false,

    'nhom' => 'nghiep_vu',

    'menu' => [
        [
            'ma' => '_mau',
            'tieu_de' => 'Mo dun mau',
            'bieu_tuong' => 'ti ti-template',
            'duong_dan' => '/mau',
            'quyen' => '_mau.xem',
            'thu_tu' => 999,
            'hien_thi' => false,
            'con' => [],
        ]
    ],

    'route' => [
        ['phuong_thuc' => 'GET', 'duong_dan' => '/mau', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.xem', 'xu_ly' => [MauDieuKhien::class, 'danhSach']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/mau/them', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.them', 'xu_ly' => [MauDieuKhien::class, 'them']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/mau/luu', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.them', 'xu_ly' => [MauDieuKhien::class, 'luu']],
        ['phuong_thuc' => 'GET', 'duong_dan' => '/mau/sua/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.sua', 'xu_ly' => [MauDieuKhien::class, 'sua']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/mau/cap-nhat/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.sua', 'xu_ly' => [MauDieuKhien::class, 'capNhat']],
        ['phuong_thuc' => 'POST', 'duong_dan' => '/mau/xoa/{id}', 'yeu_cau_dang_nhap' => true, 'quyen' => '_mau.xoa', 'xu_ly' => [MauDieuKhien::class, 'xoa']],
    ],

    'quyen' => [
        '_mau.xem' => 'Xem du lieu',
        '_mau.them' => 'Them du lieu',
        '_mau.sua' => 'Sua du lieu',
        '_mau.xoa' => 'Xoa du lieu',
    ],

    'tai_nguyen' => [
        'css' => [
            '/mo_dun/_mau/tai_nguyen/css/mau.css',
        ],
        'js' => [
            '/mo_dun/_mau/tai_nguyen/js/mau.js',
        ],
    ],
];
