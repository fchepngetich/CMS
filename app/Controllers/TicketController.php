<?php

namespace App\Controllers;
use App\Models\Tickets;
use App\Models\User;
use App\Libraries\CIAuth;



use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;



class TicketController extends BaseController
{
    protected $db;
   
    protected $ticketModel;
    protected $userModel;

    public function __construct() {
        $this->db= db_connect();
        $this->ticketModel = new Tickets();
        $this->userModel = new User();
    }
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

    public function showAssignForm() {
        $full_name = CIAuth::fullName();
        $data['full_name']= $full_name;
        $data['tickets'] = $this->ticketModel->where('status', 'open')->findAll(); 
        $data['agents'] = $this->userModel->where('role', '2')->findAll();
        return view('backend/pages/assign-tickets', $data);
    }
    public function assignTicket()
    {
        $ticketId = $this->request->getPost('ticket_id');
        $agentId = $this->request->getPost('agent_id');
    
        // Assuming `$this->db` is your CodeIgniter database instance
        $data = [
            'assigned_to' => $agentId,
            'assigned_at' => date('Y-m-d H:i:s')
        ];
    
        // Get a Query Builder instance
        $builder = $this->db->table('tickets');
    
        $builder->where('id', $ticketId)
                ->update($data);
    
        return redirect()->to('admin/home')->with('success', 'Ticket assigned successfully');
    }
    
    
    
    
}