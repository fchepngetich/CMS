<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        {
            $data = array(
             'full_name' =>'Faith Chepngetich' ,
             'email' => 'faith.chepngetich@zetech.ac.ke',
             'password' =>password_hash('12345',PASSWORD_BCRYPT),
     
            );
            $this->db->table('users')->insert($data);
         }
    }
}
