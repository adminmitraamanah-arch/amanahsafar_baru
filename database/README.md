# Database Scripts - Amanah Safar

Direktori ini berisi skrip SQL untuk inisialisasi dan pengaturan database proyek Amanah Safar.

## File yang Tersedia

1. **`schema.sql`**
   - Berisi definisi skema tabel lengkap (`CREATE TABLE`) tanpa data riil.
   - Digunakan saat instalasi baru di server/lingkungan pengembang.

2. **`database_seed.sql`**
   - Berisi data awal dummy (1 akun Super Admin contoh, 3 paket umroh/haji contoh, dan voucher contoh).
   - Jalankan setelah mengimpor `schema.sql`.

3. **Folder `backup/`** *(Diabaikan oleh Git)*
   - Tempat penyimpanan salinan database lokal (`*.sql`, `*.dump`).
   - File di dalam folder ini otomatis diabaikan oleh Git untuk mencegah kebocoran data sensitif operasional (PII).

---

## Cara Inisialisasi Database

### Opsi A: Melalui phpMyAdmin / MySQL CLI
```bash
# 1. Buat database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_mitra_amanah;"

# 2. Impor struktur tabel
mysql -u root -p db_mitra_amanah < database/schema.sql

# 3. Impor data sampel awal
mysql -u root -p db_mitra_amanah < database/database_seed.sql
```

### Opsi B: Melalui CodeIgniter Spark (Migrations)
Proyek ini juga mendukung migrasi resmi CodeIgniter 4 yang tersimpan di `app/Database/Migrations/`:
```bash
php spark migrate
```
