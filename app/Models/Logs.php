<?php

namespace App\Models;

use CodeIgniter\Model;

class Logs extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'message', 'created_at'];
    protected $useTimestamps = true;
}
