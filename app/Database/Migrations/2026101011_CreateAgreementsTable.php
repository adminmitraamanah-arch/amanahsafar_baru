<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgreementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['pusat', 'cabang', 'manager_mitra', 'mitra'],
            ],
            'versi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'isi' => [
                'type' => 'TEXT',
            ],
            'aktif' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addKey(['role', 'aktif']);
        $this->forge->createTable('agreements');
    }

    public function down()
    {
        $this->forge->dropTable('agreements');
    }
}
