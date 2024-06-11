<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToTickets extends Migration
{
    public function up()
    {
        
        $this->forge->addColumn('tickets', [
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'after' => 'id', 
            ],
        ]);

        $this->db->query('ALTER TABLE tickets ADD CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE tickets DROP FOREIGN KEY tickets_user_id_foreign');
        
        $this->forge->dropColumn('tickets', 'user_id');
    }
}
