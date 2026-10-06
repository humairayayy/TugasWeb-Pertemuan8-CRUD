CREATE DATABASE IF NOT EXISTS inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

DROP TABLE IF EXISTS log_aktivitas;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;

CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telepon VARCHAR(20) NULL
) ENGINE=InnoDB;

CREATE TABLE produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  kategori_id INT NOT NULL,
  supplier_id INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  stok INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_produk_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_produk_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Bonus: log aktivitas untuk transaksi delete
CREATE TABLE log_aktivitas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aksi VARCHAR(20) NOT NULL,
  keterangan VARCHAR(255) NOT NULL,
  waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO kategori (nama) VALUES
('Elektronik'),('Alat Tulis'),('Perabot'),('Makanan Ringan'),('Minuman');

INSERT INTO supplier (nama, telepon) VALUES
('PT Maju Jaya','061-111222'),('CV Sinar Abadi','061-333444'),
('UD Berkah','0812-1111-2222'),('PT Nusantara Supply','0813-3333-4444'),
('CV Mitra Sejati','0821-5555-6666');

INSERT INTO produk (nama, kategori_id, supplier_id, harga, stok) VALUES
('Mouse Wireless',1,1,85000,40),
('Pulpen Gel Hitam',2,2,3500,300),
('Kursi Kantor',3,3,750000,12),
('Keripik Singkong',4,4,12000,150),
('Teh Botol 450ml',5,5,5000,200),
('Keyboard Mekanik',1,1,450000,25);
