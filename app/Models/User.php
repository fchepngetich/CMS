<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['full_name','email','password','picture','bio','role','password_reset_required','first_login'];


    public function getFullNameById($userId)
    {
        $user = $this->find($userId);
        return $user ? $user['full_name'] : 'Unknown';
    }
   
}
