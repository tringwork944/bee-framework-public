CREATE TABLE IF NOT EXISTS quyen_vai_tro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vai_tro_id INT NOT NULL,
  ma_quyen VARCHAR(120) NOT NULL,
  UNIQUE KEY uq_vai_tro_quyen (vai_tro_id, ma_quyen)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
