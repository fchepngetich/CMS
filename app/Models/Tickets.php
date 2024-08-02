<?php

namespace App\Models;

use CodeIgniter\Model;

class Tickets extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    
    protected $allowedFields = [
        'subject', 
        'description', 
        'user_id', 
        'status', 
        'category_id', 
        'read_status', 
        'dev_remarks', 
        'dev_status', 
        'date_completed'
    ];
    public function replies()
    {
        return $this->hasMany(Replies::class);
    }

    public function assignTicket($ticketId, $agentId)
    {
        return $this->where('id', $ticketId)
                    ->set(['assigned_to' => $agentId])
                    ->update();
    }
       
}
