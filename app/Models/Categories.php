<?php

namespace App\Models;

use CodeIgniter\Model;

class Categories extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
  
    protected $allowedFields = ['name', 'description','last_ticket_id','unread_count'];
    
    public function getCategoriesWithTicketCounts()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('categories');
        $builder->select('categories.id, categories.name');
        $builder->select('(SELECT COUNT(*) FROM tickets WHERE tickets.category_id = categories.id) AS total_tickets');
        $builder->select('(SELECT COUNT(*) FROM tickets WHERE tickets.category_id = categories.id AND tickets.status = "open") AS pending_tickets');
        $builder->select('(SELECT COUNT(*) FROM tickets WHERE tickets.category_id = categories.id AND tickets.status = "closed") AS closed_tickets');
        $query = $builder->get();
        return $query->getResultArray();
    }

}
