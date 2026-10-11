<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVoucherFormatColumns extends Migration
{
    public function up()
    {
        // 1. Tambah kolom kode_pembina ke tabel users jika belum ada
        if (!$this->db->fieldExists('kode_pembina', 'users')) {
            $this->forge->addColumn('users', [
                'kode_pembina' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                    'null' => true,
                    'after' => 'pembina_id',
                ],
            ]);
            $this->db->query('ALTER TABLE `users` ADD INDEX `idx_users_kode_pembina` (`kode_pembina`)');
        }

        // 2. Tambah kolom nomor_urut ke tabel vouchers jika belum ada
        if (!$this->db->fieldExists('nomor_urut', 'vouchers')) {
            $this->forge->addColumn('vouchers', [
                'nomor_urut' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'pembina_id',
                ],
            ]);
            $this->db->query('ALTER TABLE `vouchers` ADD INDEX `idx_vouchers_pembina_urut` (`pembina_id`, `nomor_urut`)');
        }

        // 3. Inisialisasi kode_pembina permanen awal untuk akun pembina yang sudah ada
        // Pusat: '001' (Pusat tunggal)
        $this->db->query("UPDATE `users` SET `kode_pembina` = '001' WHERE `role` = 'pusat' AND (`kode_pembina` IS NULL OR `kode_pembina` = '')");

        // Cabang: 001, 002, dst.
        $cabangs = $this->db->table('users')->where('role', 'cabang')->orderBy('id', 'ASC')->get()->getResultArray();
        $cbNum = 1;
        foreach ($cabangs as $cb) {
            if (empty($cb['kode_pembina'])) {
                $code = sprintf('%03d', $cbNum++);
                $this->db->table('users')->where('id', $cb['id'])->update(['kode_pembina' => $code]);
            }
        }

        // Manager Mitra: 001, 002, dst.
        $managers = $this->db->table('users')->where('role', 'manager_mitra')->orderBy('id', 'ASC')->get()->getResultArray();
        $mgNum = 1;
        foreach ($managers as $mg) {
            if (empty($mg['kode_pembina'])) {
                $code = sprintf('%03d', $mgNum++);
                $this->db->table('users')->where('id', $mg['id'])->update(['kode_pembina' => $code]);
            }
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('nomor_urut', 'vouchers')) {
            $this->forge->dropColumn('vouchers', 'nomor_urut');
        }

        if ($this->db->fieldExists('kode_pembina', 'users')) {
            $this->forge->dropColumn('users', 'kode_pembina');
        }
    }
}
