<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        {
            $data = array(
             'subject' =>'Change Management System Change',
             'user_id'=>'1',
             'description' => 'This is the first ticket description.Lorem Ipsum is simply dummy text of the printing and typesetting industry.
              Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and 
             ',
             'status' =>'open',
     
            );
            $this->db->table('tickets')->insert($data);
         }
    }
}
