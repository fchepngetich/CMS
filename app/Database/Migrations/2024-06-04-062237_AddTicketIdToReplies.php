<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTicketIdToReplies extends Migration
{
    public function up()
    {
        
        $this->forge->addColumn('replies', [
            'ticket_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'after' => 'id', 
            ],
        ]);

        $this->db->query('ALTER TABLE replies ADD CONSTRAINT FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE replies DROP FOREIGN KEY tickets_replies_id_foreign');
        
        $this->forge->dropColumn('replies', 'ticket_id');
    }
}
