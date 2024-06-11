<?php

namespace App\Models;

use CodeIgniter\Model;

class Replies extends Model
{
    protected $table            = 'replies';
    protected $primaryKey       = 'id';
  
    protected $allowedFields = ['ticket_id', 'user_id', 'description', 'created_at'];

    public function getTicketReplies($ticketId)
    {
        return $this->where('ticket_id', $ticketId)->findAll();
    }
    
}
