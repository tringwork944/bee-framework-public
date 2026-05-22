CREATE TABLE IF NOT EXISTS mo_dun (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma VARCHAR(100) NOT NULL UNIQUE,
    ten VARCHAR(150) NOT NULL,
    mo_ta TEXT NULL,
    phien_ban VARCHAR(50) NULL,
    tac_gia VARCHAR(150) NULL,
    website VARCHAR(255) NULL,
    duong_dan VARCHAR(255) NOT NULL,
    trang_thai ENUM('chua_cai_dat', 'da_cai_dat', 'dang_bat', 'dang_tat', 'loi') DEFAULT 'chua_cai_dat',
    la_mo_dun_loi TINYINT DEFAULT 0,
    loi TEXT NULL,
    ngay_cai_dat DATETIME NULL,
    ngay_kich_hoat DATETIME NULL,
    ngay_tat DATETIME NULL,
    ngay_cap_nhat DATETIME NULL,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
