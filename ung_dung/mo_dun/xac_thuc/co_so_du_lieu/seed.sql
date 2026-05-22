INSERT INTO vai_tro (id, ma, ten) VALUES (1, 'admin', 'Quan tri')
ON DUPLICATE KEY UPDATE ten = VALUES(ten);
