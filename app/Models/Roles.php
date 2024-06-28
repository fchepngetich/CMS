<?php

namespace App\Models;

use CodeIgniter\Model;

class Roles extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name'];

    public function   getRoleNameById($roleId)
    {
        $name='Faith';
        return $name;
    }

}
