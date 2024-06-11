<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RepliesSeeder extends Seeder
{
    public function run()
    {
        {
            $data = array(
             
             'description' => 'This is noted
             ',
             'user_id' =>1,
     
            );
            $this->db->table('replies')->insert($data);
         }
    }
}
