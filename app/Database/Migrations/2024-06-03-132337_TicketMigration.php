<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TicketMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            
            'id'=>[
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
           

            'subject' =>[
                'type' =>'VARCHAR',
                'constraint' => '255',

            ],
            'description'=>[
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
           
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp',

            ]);
            $this->forge->addKey('id',true);
            $this->forge->createTable('tickets');
    }

    public function down()
    {
        $this->forge->dropTable('tickets');
    }
}
