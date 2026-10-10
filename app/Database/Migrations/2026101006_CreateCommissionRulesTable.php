<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionRulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['manager_mitra', 'mitra', 'pusat', 'cabang'],
            ],
            'tipe' => [
                'type' => 'ENUM',
                'constraint' => ['persen', 'nominal'],
            ],
            'nilai' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
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
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('role');
        $this->forge->createTable('commission_rules');
    }

    public function down()
    {
        $this->forge->dropTable('commission_rules');
    }
}
