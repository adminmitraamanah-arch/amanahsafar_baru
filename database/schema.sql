CREATE DATABASE IF NOT EXISTS db_mitra_amanah;
USE db_mitra_amanah;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  no_wa VARCHAR(20) UNIQUE NOT NULL,
  domisili VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin','admin','pusat','cabang','manager_mitra','mitra') DEFAULT 'mitra',
  pembina_id INT UNSIGNED NULL,
  status ENUM('aktif','nonaktif','pending') DEFAULT 'pending',
  verification_status ENUM('belum_verifikasi','menunggu_review','terverifikasi','ditolak') DEFAULT 'belum_verifikasi',
  agreement_accepted_at DATETIME NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  FOREIGN KEY (pembina_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_email (email),
  KEY idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vouchers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(9) UNIQUE NOT NULL,
  role_tujuan ENUM('pusat','cabang','manager_mitra','mitra') NOT NULL,
  pembina_id INT UNSIGNED NULL,
  status ENUM('tersedia','terpakai','dinonaktifkan') DEFAULT 'tersedia',
  expired_at DATETIME NULL,
  used_by INT UNSIGNED NULL,
  used_at DATETIME NULL,
  created_by INT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (pembina_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (used_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  KEY idx_kode (kode),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS packages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  kategori VARCHAR(100),
  harga DECIMAL(12,2) NOT NULL,
  deskripsi TEXT,
  gambar VARCHAR(255) NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_by INT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  KEY idx_status (status),
  KEY idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  no_order VARCHAR(50) UNIQUE NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  status ENUM('menunggu_pembayaran','dibayar','diproses','selesai','dibatalkan') DEFAULT 'menunggu_pembayaran',
  total DECIMAL(12,2) NOT NULL,
  bukti_transfer VARCHAR(255) NULL,
  catatan TEXT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_no_order (no_order),
  KEY idx_status (status),
  KEY idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  package_id INT UNSIGNED NOT NULL,
  qty INT UNSIGNED DEFAULT 1,
  harga_snapshot DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS commission_rules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  package_id INT UNSIGNED NULL,
  role ENUM('manager_mitra','mitra','pusat','cabang') NOT NULL,
  tipe ENUM('persen','nominal') NOT NULL,
  nilai DECIMAL(10,2) NOT NULL,
  aktif BOOLEAN DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS commissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  nominal_snapshot DECIMAL(12,2) NOT NULL,
  status ENUM('pending','disetujui','dibayar','dibatalkan') DEFAULT 'pending',
  paid_at DATETIME NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_status (status),
  KEY idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  caption TEXT,
  tenggat DATETIME NULL,
  target_role VARCHAR(100),
  created_by INT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS task_files (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  task_id INT UNSIGNED NOT NULL,
  path VARCHAR(255) NOT NULL,
  tipe VARCHAR(50),
  ukuran INT UNSIGNED,
  created_at DATETIME NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS task_progress (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  task_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  status ENUM('belum_dibagikan','sudah_dibagikan') DEFAULT 'belum_dibagikan',
  bukti VARCHAR(255) NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_task_user (task_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS agreements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role ENUM('pusat','cabang','manager_mitra','mitra') NOT NULL,
  versi VARCHAR(20),
  isi TEXT NOT NULL,
  aktif BOOLEAN DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  KEY idx_role_aktif (role, aktif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS agreement_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  agreement_id INT UNSIGNED NOT NULL,
  accepted_at DATETIME NOT NULL,
  ip_address VARCHAR(45),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (agreement_id) REFERENCES agreements(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  KEY idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS verifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  ktp_path VARCHAR(255) NULL,
  swafoto_path VARCHAR(255) NULL,
  nama_bank VARCHAR(100) NULL,
  nomor_rekening VARCHAR(50) NULL,
  nama_pemilik_rekening VARCHAR(255) NULL,
  status ENUM('belum_verifikasi','menunggu_review','terverifikasi','ditolak') DEFAULT 'belum_verifikasi',
  reviewed_by INT UNSIGNED NULL,
  catatan TEXT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  KEY idx_user_id (user_id),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  aksi VARCHAR(100) NOT NULL,
  entitas VARCHAR(100) NOT NULL,
  entitas_id INT UNSIGNED NULL,
  data_lama TEXT NULL,
  data_baru TEXT NULL,
  ip_address VARCHAR(45),
  created_at DATETIME NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  KEY idx_user_id (user_id),
  KEY idx_entitas (entitas),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS landing_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(50) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS hero_slides (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) NULL,
  badge_text VARCHAR(100) NULL,
  description TEXT NULL,
  image_url TEXT NOT NULL,
  media_type ENUM('image','video') DEFAULT 'image',
  cta_primary_text VARCHAR(100) NULL,
  cta_primary_url VARCHAR(255) NULL,
  cta_secondary_text VARCHAR(100) NULL,
  cta_secondary_url VARCHAR(255) NULL,
  order_num INT DEFAULT 0,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_status_order (status, order_num)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_jamaah VARCHAR(255) NOT NULL,
  foto VARCHAR(255) NULL,
  program_paket VARCHAR(255) NOT NULL,
  tahun_keberangkatan VARCHAR(50) NULL,
  kota_asal VARCHAR(100) NULL,
  testimoni TEXT NOT NULL,
  rating INT DEFAULT 5,
  order_num INT DEFAULT 1,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  KEY idx_status_order (status, order_num)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
