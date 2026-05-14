CREATE TABLE IF NOT EXISTS vai_tro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(50) NOT NULL UNIQUE,
  ten VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tai_khoan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vai_tro_id INT NOT NULL,
  ho_ten VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  so_dien_thoai VARCHAR(30) NULL,
  mat_khau VARCHAR(255) NOT NULL,
  trang_thai TINYINT(1) NOT NULL DEFAULT 1,
  tao_luc TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME NULL,
  cap_nhat_luc TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tai_khoan_vai_tro FOREIGN KEY (vai_tro_id) REFERENCES vai_tro(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quyen_vai_tro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vai_tro_id INT NOT NULL,
  ma_quyen VARCHAR(120) NOT NULL,
  UNIQUE KEY uq_vai_tro_quyen (vai_tro_id, ma_quyen),
  CONSTRAINT fk_quyen_vai_tro FOREIGN KEY (vai_tro_id) REFERENCES vai_tro(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS menu_he_thong (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma VARCHAR(150) NOT NULL UNIQUE,
  mo_dun_ma VARCHAR(100) NULL,
  nhom VARCHAR(50) NULL,
  cha_id INT NULL,
  tieu_de VARCHAR(150) NOT NULL,
  bieu_tuong VARCHAR(100) NULL,
  duong_dan VARCHAR(255) NULL,
  quyen VARCHAR(150) NULL,
  thu_tu INT DEFAULT 999,
  hien_thi TINYINT DEFAULT 1,
  trang_thai TINYINT DEFAULT 1,
  ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO vai_tro (id, ma, ten) VALUES (1, 'admin', 'Quan tri')
ON DUPLICATE KEY UPDATE ten = VALUES(ten);

INSERT INTO quyen_vai_tro (vai_tro_id, ma_quyen) VALUES
(1, 'tong_quan.xem'),
(1, 'tai_khoan.xem'),
(1, 'tai_khoan.sua'),
(1, 'quan_ly_mo_dun.xem'),
(1, 'quan_ly_mo_dun.chi_tiet'),
(1, 'quan_ly_mo_dun.bat_tat'),
(1, 'quan_ly_mo_dun.kiem_tra')
ON DUPLICATE KEY UPDATE ma_quyen = VALUES(ma_quyen);

INSERT INTO tai_khoan (vai_tro_id, ho_ten, email, mat_khau, trang_thai)
VALUES (1, 'Quan tri he thong', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1)
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), trang_thai = VALUES(trang_thai);
