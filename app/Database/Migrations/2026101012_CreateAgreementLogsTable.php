<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgreementLogsTable extends Migration
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
            ],
            'agreement_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'accepted_at' => [
                'type' => 'DATETIME',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('agreement_id', 'agreements', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addKey('user_id');
        $this->forge->createTable('agreement_logs');
    }

    public function down()
    {
        $this->forge->dropTable('agreement_logs');
    }
}
