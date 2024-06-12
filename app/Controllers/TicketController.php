<?php

namespace App\Controllers;
use App\Models\Tickets;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TicketController extends BaseController
{
    public function closeTicket($id)
    {
        $ticketModel = new Tickets();
        $ticketModel->update($id, ['status' => 'closed']);
        return redirect()->to('/admin/home');
    }

    public function reopenTicket($id)
    {
        $ticketModel = new Tickets();
        $ticketModel->update($id, ['status' => 'open']);
        return redirect()->to('/admin/home');
    }
}
