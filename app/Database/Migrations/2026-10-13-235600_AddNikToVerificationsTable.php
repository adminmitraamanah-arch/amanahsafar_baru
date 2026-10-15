<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNikToVerificationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('verifications') && !$this->db->fieldExists('nik', 'verifications')) {
            $this->forge->addColumn('verifications', [
                'nik' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 16,
                    'null'       => true,
                    'after'      => 'user_id',
                ],
            ]);
            $this->db->query("ALTER TABLE verifications ADD INDEX idx_verifications_nik (nik)");
        }
    }

    public function down()
    {
        if ($this->db->tableExists('verifications') && $this->db->fieldExists('nik', 'verifications')) {
            $this->forge->dropColumn('verifications', 'nik');
        }
    }
}
