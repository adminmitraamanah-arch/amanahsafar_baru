<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTasksForPromotionalContent extends Migration
{
    public function up()
    {
        $fields = [
            'kategori' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'Promosi Paket Umrah',
                'null' => true,
                'after' => 'judul',
            ],
            'copywriting' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'deskripsi',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'copywriting',
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'catatan',
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'file_path',
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'file_type',
            ],
            'file_size' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'after' => 'file_name',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published'],
                'default' => 'published',
                'after' => 'file_size',
            ],
        ];

        $this->forge->addColumn('tasks', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tasks', [
            'kategori',
            'copywriting',
            'catatan',
            'file_path',
            'file_type',
            'file_name',
            'file_size',
            'status',
        ]);
    }
}
