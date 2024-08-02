<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Logs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'data' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('logs');
    }

    public function down()
    {
        $this->forge->dropTable('logs');
    }
}
