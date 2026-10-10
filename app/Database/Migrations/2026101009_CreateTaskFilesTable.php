<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskFilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'task_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'ukuran' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('task_files');
    }

    public function down()
    {
        $this->forge->dropTable('task_files');
    }
}
