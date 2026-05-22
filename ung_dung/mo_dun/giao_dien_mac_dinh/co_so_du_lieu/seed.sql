INSERT INTO giao_dien (ma, ten, mo_dun_ma, phien_ban, dang_kich_hoat, la_mac_dinh)
VALUES ('giao_dien_mac_dinh', 'Giao dien mac dinh', 'giao_dien_mac_dinh', '1.0.0', 1, 1)
ON DUPLICATE KEY UPDATE ten = VALUES(ten), mo_dun_ma = VALUES(mo_dun_ma), phien_ban = VALUES(phien_ban);
