<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoProfilToUsersTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('users') && !$this->db->fieldExists('foto_profil', 'users')) {
            $this->forge->addColumn('users', [
                'foto_profil' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'domisili',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users') && $this->db->fieldExists('foto_profil', 'users')) {
            $this->forge->dropColumn('users', 'foto_profil');
        }
    }
}
