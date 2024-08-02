<?php

namespace App\Controllers;
use App\Models\Tickets;
use App\Models\Categories;
use App\Models\User;
use App\Libraries\CIAuth;



use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;



class TicketController extends BaseController
{
    protected $db;
   
    protected $ticketModel;
    protected $userModel;
    protected $categoryModel;

    public function __construct() {
        $this->db= db_connect();
        $this->ticketModel = new Tickets();
        $this->userModel = new User();
        $this->categoryModel = new Categories();

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


    public function showAssignForm()
    {
        $full_name = CIAuth::fullName();
        $data['full_name'] = $full_name;
        $data['tickets'] = $this->ticketModel->where('status', 'open')->findAll();
        $data['agents'] = $this->userModel->where('role', '2')->findAll();
        $data['categories'] = $this->categoryModel->findAll();

        return view('backend/pages/assign-tickets', $data);
    }

 public function assignTicket()
{
    $ticketId = $this->request->getPost('ticket_id');
    $agentIds = $this->request->getPost('agent_ids');

    log_message('debug', 'Received ticket_id: ' . $ticketId);
    log_message('debug', 'Received agent_ids: ' . json_encode($agentIds));

    // Validation
    if (!$ticketId || !$agentIds) {
        return $this->response->setJSON(['status' => 0, 'msg' => 'Ticket ID and Agent IDs are required.']);
    }

    $agentIdsStr = implode(',', $agentIds);

    $data = [
        'assigned_at' => date('Y-m-d H:i:s'),
        'assigned_to' => $agentIdsStr
    ];

    $builder = $this->db->table('tickets');

    $this->db->transStart();

    if (!$builder->where('id', $ticketId)->update($data)) {
        log_message('error', 'Failed to update ticket: ' . $ticketId);
        $this->db->transRollback();
        return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to update ticket.']);
    }

    $this->db->transComplete();

    if ($this->db->transStatus() === FALSE) {
        log_message('error', 'Transaction failed for ticket: ' . $ticketId);
        return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to assign ticket.']);
    } else {
        log_message('debug', 'Ticket assigned successfully: ' . $ticketId);
        return $this->response->setJSON(['status' => 1, 'msg' => 'Ticket assigned successfully.']);
    }
}
public function assignedTickets()
{
    $developerId =  CIAuth::id();
    $full_name = CIAuth::fullName();

    $builder = $this->db->table('tickets');
    $tickets = $builder->select('*')
                       ->where('assigned_to', $developerId) 
                       ->get()
                       ->getResult();

    $data['tickets'] = $tickets;
    $data['full_name'] = $full_name;

    return view('backend/pages/assigned-tickets', $data);
}

public function addRemarks()
{
    if ($this->request->getMethod() === 'POST') {
        $ticketId = $this->request->getPost('ticket_id');
        $remarks = $this->request->getPost('remarks');
        $Status = 'closed';

        if (empty($ticketId) || empty($remarks)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 0, 'msg' => 'Invalid input.']);
        }

        $ticketModel = new Tickets();

        $data = [
            'dev_remarks' => $remarks,
            'status' => $Status,
        ];

        if ($Status == 1) {
            $data['date_completed'] = date('Y-m-d H:i:s');
        }


        if ($ticketModel->update($ticketId, $data)) {
            session()->setFlashdata('success', 'Remarks added successfully');
            

            return redirect()->to(base_url('admin/tickets/assigned'));
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 0, 'msg' => 'Failed to add remarks.']);
        }
    }

    return $this->response->setStatusCode(405)->setJSON(['status' => 0, 'msg' => 'Method not allowed.']);
}

public function reportsTickets()
{
    $ticketModel = new Tickets();
    $full_name = CIAuth::fullName();
    $data['full_name'] = $full_name;

    $data['tickets'] = $ticketModel->findAll();

    return view('backend/pages/ticket-reports', $data);
}


    public function getOpenTicketsByCategory()
    {
        $categoryId = $this->request->getPost('category_id');

        if (!$categoryId) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Category ID is required.']);
        }

        $tickets = $this->ticketModel->where('category_id', $categoryId)
                                     ->where('status', 'open')
                                     ->findAll();

        return $this->response->setJSON(['status' => 1, 'tickets' => $tickets]);
    }
}
