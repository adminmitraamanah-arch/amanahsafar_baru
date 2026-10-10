USE db_mitra_amanah;

ALTER TABLE vouchers MODIFY COLUMN kode VARCHAR(32) NOT NULL;

-- Insert Super Admin (ignore if already inserted)
INSERT IGNORE INTO users (nama_lengkap, email, no_wa, domisili, password_hash, role, status, verification_status, agreement_accepted_at, created_at, updated_at)
VALUES ('Super Administrator', 'admin@amanahsafar.com', '081234567890', 'Jakarta', '$2y$10$IwoaZL1wdCWGwNbde27rlun97oYKLT06kadShjGU4gpsp8WiTJuJK', 'super_admin', 'aktif', 'terverifikasi', NOW(), NOW(), NOW());

-- Insert Sample Packages
INSERT INTO packages (nama, kategori, harga, deskripsi, gambar, status, created_by, created_at, updated_at)
VALUES 
('Paket Umroh Reguler 9 Hari', 'Umroh', 28500000.00, 'Paket ibadah umroh reguler 9 hari bersama maskapai Saudia Airlines, hotel bintang 4 di Makkah dan Madinah, termasuk manasik dan muthawwif berpengalaman.', NULL, 'aktif', 1, NOW(), NOW()),
('Paket Umroh VIP Plus Turki 12 Hari', 'Umroh Plus', 39900000.00, 'Perjalanan ibadah umroh dipadukan dengan wisata sejarah di Istanbul dan Cappadocia. Fasilitas hotel bintang 5.', NULL, 'aktif', 1, NOW(), NOW()),
('Paket Haji Khusus Kuota Resmi', 'Haji', 175000000.00, 'Program Haji Khusus resmi Kementerian Agama dengan masa tunggu lebih singkat dan fasilitas hotel ring 1.', NULL, 'aktif', 1, NOW(), NOW());

-- Insert Sample Voucher
INSERT INTO vouchers (kode, role_tujuan, pembina_id, status, created_by, created_at)
VALUES ('VCH-MTR01', 'mitra', 1, 'tersedia', 1, NOW());
