<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommerceAndCommissionSystem extends Migration
{
    public function up()
    {
        // 1. Perbesar vouchers.kode menjadi VARCHAR(25) (Fix BUG-05)
        $this->db->query("ALTER TABLE `vouchers` MODIFY COLUMN `kode` VARCHAR(25) NOT NULL");

        // 2. Tambah kolom komisi dan minimal DP di tabel packages
        $packageFields = [
            'komisi_mitra' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'null' => true,
                'after' => 'harga',
            ],
            'komisi_manager' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'null' => true,
                'after' => 'komisi_mitra',
            ],
            'komisi_cabang_pusat' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'null' => true,
                'after' => 'komisi_manager',
            ],
            'total_pool_komisi' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'null' => true,
                'after' => 'komisi_cabang_pusat',
            ],
            'minimal_dp_default' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'null' => true,
                'after' => 'total_pool_komisi',
            ],
        ];

        foreach ($packageFields as $fieldName => $colDef) {
            if (!$this->db->fieldExists($fieldName, 'packages')) {
                $this->forge->addColumn('packages', [$fieldName => $colDef]);
            }
        }

        // 3. Tabel package_dp_rules (Model A - Tanggal Kalender Promo DP)
        if (!$this->db->tableExists('package_dp_rules')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'package_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'tanggal_mulai' => [
                    'type' => 'DATE',
                ],
                'tanggal_selesai' => [
                    'type' => 'DATE',
                ],
                'nominal_dp' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'default' => 0.00,
                ],
                'keterangan' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('package_dp_rules', true);
            $this->db->query("ALTER TABLE `package_dp_rules` ADD INDEX `idx_dp_date_range` (`package_id`, `tanggal_mulai`, `tanggal_selesai`)");
        }

        // 4. Perbarui kolom tabel orders untuk mendukung akumulasi tagihan dan status bertahap
        $orderFields = [
            'tipe_pembayaran_pilihan' => [
                'type' => 'ENUM',
                'constraint' => ['dp', 'full'],
                'default' => 'dp',
                'after' => 'total',
            ],
            'nominal_dp_minimal' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'after' => 'tipe_pembayaran_pilihan',
            ],
            'total_dibayar' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'after' => 'nominal_dp_minimal',
            ],
            'sisa_tagihan' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00,
                'after' => 'total_dibayar',
            ],
            'status_pembayaran' => [
                'type' => 'ENUM',
                'constraint' => ['menunggu_pembayaran', 'dp_diterima', 'lunas'],
                'default' => 'menunggu_pembayaran',
                'after' => 'sisa_tagihan',
            ],
        ];

        foreach ($orderFields as $fieldName => $colDef) {
            if (!$this->db->fieldExists($fieldName, 'orders')) {
                $this->forge->addColumn('orders', [$fieldName => $colDef]);
            }
        }

        // Sinkronisasi default sisa_tagihan = total untuk order lama
        $this->db->query("UPDATE `orders` SET `sisa_tagihan` = `total` WHERE `sisa_tagihan` = 0 AND `total_dibayar` = 0");

        // 5. Tabel order_payments (Opsi A - Riwayat Transfer DP dan Pelunasan tidak saling menimpa)
        if (!$this->db->tableExists('order_payments')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'no_transaksi' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'tipe_pembayaran' => [
                    'type' => 'ENUM',
                    'constraint' => ['dp', 'pelunasan', 'full'],
                    'default' => 'dp',
                ],
                'nominal' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'default' => 0.00,
                ],
                'bukti_transfer' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'nama_bank_pengirim' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
                'nomor_rekening_pengirim' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
                'nama_pengirim' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                    'null' => true,
                ],
                'catatan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['menunggu_verifikasi', 'diterima', 'ditolak'],
                    'default' => 'menunggu_verifikasi',
                ],
                'catatan_admin' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'verified_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'verified_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('order_payments', true);
        }

        // 6. Sinkronkan kolom tabel commissions untuk mendukung buku mutasi perbankan & 4 skenario
        $commFields = [
            'role_penerima' => [
                'type' => 'ENUM',
                'constraint' => ['mitra', 'manager_mitra', 'cabang', 'pusat'],
                'null' => true,
                'after' => 'user_id',
            ],
            'jenis_komisi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'role_penerima',
            ],
            'keterangan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'nominal_snapshot',
            ],
            'cair_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'paid_at',
            ],
        ];

        foreach ($commFields as $fieldName => $colDef) {
            if (!$this->db->fieldExists($fieldName, 'commissions')) {
                $this->forge->addColumn('commissions', [$fieldName => $colDef]);
            }
        }

        // Modifikasi kolom status pada commissions agar mendukung 'potensi', 'siap_cair', 'ditarik', 'dibatalkan'
        $this->db->query("ALTER TABLE `commissions` MODIFY COLUMN `status` ENUM('potensi', 'siap_cair', 'ditarik', 'dibatalkan', 'pending', 'dibayar') DEFAULT 'potensi'");

        // 7. Tabel withdrawals (Request Tarik Dana Komisi Opsi A)
        if (!$this->db->tableExists('withdrawals')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'no_penarikan' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'nominal' => [
                    'type' => 'DECIMAL',
                    'constraint' => '12,2',
                    'default' => 0.00,
                ],
                'nama_bank' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'nomor_rekening' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'nama_pemilik_rekening' => [
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['menunggu_transfer', 'selesai', 'ditolak'],
                    'default' => 'menunggu_transfer',
                ],
                'bukti_transfer' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'catatan_admin' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'processed_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'processed_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('no_penarikan');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
            $this->forge->createTable('withdrawals', true);
        }
    }

    public function down()
    {
        // Safe rollback
        if ($this->db->tableExists('withdrawals')) {
            $this->forge->dropTable('withdrawals');
        }
        if ($this->db->tableExists('order_payments')) {
            $this->forge->dropTable('order_payments');
        }
        if ($this->db->tableExists('package_dp_rules')) {
            $this->forge->dropTable('package_dp_rules');
        }
    }
}
