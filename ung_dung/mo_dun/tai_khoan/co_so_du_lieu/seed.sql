INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES
(1, 'tai_khoan.xem'),
(1, 'tai_khoan.them'),
(1, 'tai_khoan.sua'),
(1, 'tai_khoan.xoa')
ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen);
