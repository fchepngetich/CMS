<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepliesMigration extends Migration
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
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('replies');
    }

    public function down()
    {
        $this->forge->dropTable('replies');
    }
}
