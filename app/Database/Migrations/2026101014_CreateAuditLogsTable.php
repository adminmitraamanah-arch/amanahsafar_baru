<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'aksi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'entitas' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'entitas_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'data_lama' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_baru' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addKey('user_id');
        $this->forge->addKey('entitas');
        $this->forge->addKey('created_at');
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
