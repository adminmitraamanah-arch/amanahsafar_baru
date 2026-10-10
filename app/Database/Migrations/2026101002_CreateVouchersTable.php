<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kode' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
                'unique' => true,
            ],
            'role_tujuan' => [
                'type' => 'ENUM',
                'constraint' => ['pusat', 'cabang', 'manager_mitra', 'mitra'],
            ],
            'pembina_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['tersedia', 'terpakai', 'dinonaktifkan'],
                'default' => 'tersedia',
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'used_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => true,
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
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pembina_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('used_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addKey('kode');
        $this->forge->addKey('status');
        $this->forge->createTable('vouchers');
    }

    public function down()
    {
        $this->forge->dropTable('vouchers');
    }
}
