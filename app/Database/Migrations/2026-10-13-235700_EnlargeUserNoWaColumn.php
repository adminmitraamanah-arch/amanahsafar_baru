<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnlargeUserNoWaColumn extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('users')) {
            $this->forge->modifyColumn('users', [
                'no_wa' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users')) {
            $this->forge->modifyColumn('users', [
                'no_wa' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                ],
            ]);
        }
    }
}
