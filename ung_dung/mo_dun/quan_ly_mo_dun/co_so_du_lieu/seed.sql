INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES
(1, 'quan_ly_mo_dun.xem'),
(1, 'quan_ly_mo_dun.cai_dat'),
(1, 'quan_ly_mo_dun.kich_hoat'),
(1, 'quan_ly_mo_dun.tat'),
(1, 'quan_ly_mo_dun.go_cai_dat'),
(1, 'quan_ly_mo_dun.kiem_tra'),
(1, 'quan_ly_mo_dun.tai_len')
ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen);
