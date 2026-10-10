<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'qty' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 1,
            ],
            'harga_snapshot' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
