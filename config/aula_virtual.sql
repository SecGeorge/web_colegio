ALTER TABLE alumn
  ADD COLUMN IF NOT EXISTS login VARCHAR(30) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS clave VARCHAR(64) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS folder (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  block_id INT(11) NOT NULL,
  user_id INT(11) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY folder_block (block_id),
  KEY folder_user (user_id),
  CONSTRAINT folder_ibfk_1 FOREIGN KEY (block_id) REFERENCES block (id),
  CONSTRAINT folder_ibfk_2 FOREIGN KEY (user_id) REFERENCES usuario (idusuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE IF NOT EXISTS document (
  id INT(11) NOT NULL AUTO_INCREMENT,
  folder_id INT(11) NOT NULL,
  title VARCHAR(150) NOT NULL,
  filename VARCHAR(255) NOT NULL,
  originalname VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY document_folder (folder_id),
  CONSTRAINT document_ibfk_1 FOREIGN KEY (folder_id) REFERENCES folder (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
