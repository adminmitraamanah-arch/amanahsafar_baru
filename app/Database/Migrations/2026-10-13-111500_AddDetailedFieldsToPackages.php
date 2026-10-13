<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedFieldsToPackages extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_keberangkatan' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'nama',
            ],
            'program_durasi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'tanggal_keberangkatan',
            ],
            'maskapai' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'after' => 'program_durasi',
            ],
            'hotel_mekkah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'maskapai',
            ],
            'hotel_madinah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'hotel_mekkah',
            ],
            'harga_quad' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
                'after' => 'harga',
            ],
            'harga_triple' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
                'after' => 'harga_quad',
            ],
            'harga_double' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
                'after' => 'harga_triple',
            ],
            'seat_tersedia' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'after' => 'harga_double',
            ],
            'includes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'deskripsi',
            ],
            'excludes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'includes',
            ],
        ];

        foreach ($fields as $fieldName => $fieldConfig) {
            if (!$this->db->fieldExists($fieldName, 'packages')) {
                $this->forge->addColumn('packages', [$fieldName => $fieldConfig]);
            }
        }

        // Sinkronisasi data awal untuk paket eksisting
        $this->db->query("UPDATE `packages` SET `harga_quad` = `harga` WHERE `harga_quad` IS NULL AND `harga` > 0");
        $this->db->query("UPDATE `packages` SET `seat_tersedia` = 45 WHERE `seat_tersedia` IS NULL OR `seat_tersedia` = 0");
    }

    public function down()
    {
        $fields = [
            'tanggal_keberangkatan',
            'program_durasi',
            'maskapai',
            'hotel_mekkah',
            'hotel_madinah',
            'harga_quad',
            'harga_triple',
            'harga_double',
            'seat_tersedia',
            'includes',
            'excludes',
        ];

        foreach ($fields as $fieldName) {
            if ($this->db->fieldExists($fieldName, 'packages')) {
                $this->forge->dropColumn('packages', $fieldName);
            }
        }
    }
}
