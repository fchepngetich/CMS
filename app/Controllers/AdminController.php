<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Models\Tickets;
use App\Models\Subcategory;
use App\Models\Replies;
use App\Models\User;
use App\Models\Roles;
use \Mberecall\CI_Slugify\SlugService;
use SSP;


class AdminController extends BaseController
{
    protected $helpers = ['url','form','CIMail','CIFunctions'];
    protected $db;
    public function __construct(){
        require_once APPPATH.'ThirdParty/ssp.php';
        $this->db= db_connect();
    }
   /* public function index()
{
    $ticketModel = new Tickets();
    $repliesModel = new Replies();
    $userModel = new User();
    $full_name = CIAuth::fullName();
    $userId = CIAuth::Id();  

    $replies = $repliesModel->findAll();
    $tickets = $ticketModel->where('user_id', $userId)->findAll();

    $Singlereplies = $repliesModel->whereIn('ticket_id', array_column($tickets, 'id'))->findAll();

    return view('backend/pages/home', [
        'tickets' => $tickets,
        'replies' => $replies,
        'userModel' => $userModel,
        'full_name' => $full_name,
        'Singlereplies' => $Singlereplies
    ]);
}*/

    public function index()
    {
        $ticketModel = new Tickets();
        $repliesModel = new Replies();
        $userModel = new User();
        $full_name = CIAuth::fullName();

        $tickets = $ticketModel->findAll();
        $replies = $repliesModel->findAll();

        return view('backend/pages/home', [
            'tickets' => $tickets,
            'replies' => $replies,
            'userModel' => $userModel,
            'full_name' =>$full_name
        ]);
    }
    public function myTickets()
{
    $ticketModel = new Tickets();
    $repliesModel = new Replies();
    $userModel = new User();
    $full_name = CIAuth::fullName();
    $loggedInUserId = CIAuth::id(); 

    $tickets = $ticketModel->where('user_id', $loggedInUserId)->findAll();
    $replies = $repliesModel->findAll();

    return view('backend/pages/my-tickets', [
        'tickets' => $tickets,
        'replies' => $replies,
        'userModel' => $userModel,
        'full_name' => $full_name
    ]);
}


    public function displayReplies($ticketId)
    {
        $repliesModel = new Replies();
        $userModel = new User();
        $full_name = CIAuth::fullName();


        $replies = $repliesModel->getTicketReplies($ticketId);

        return view('backend/pages/home', [
            'replies' => $replies,
            'userModel' => $userModel,
            'ticketId' => $ticketId,
            'full_name' =>$full_name

        ]);
    }
    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail','You are Loggged out');


    }
    

    public function tickets()
    {
        $ticketModel = new Tickets();
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name; 

        $data['tickets'] = $ticketModel->findAll(); 
        return view('backend/pages/home', $data); 
    }
    public function getUsers(){
        $db = \Config\Database::connect();
        $full_name = CIAuth::fullName();
        $roleModel = new Roles(); 
        $roles = $roleModel->findAll();

        $userModel = new User();
        $data['full_name'] = $full_name; 
        $data['roles'] = $roles; 
        $data['users'] = $userModel->findAll();
        return view('backend/pages/users', $data); 
    }
    
    
    public function addUser(){
        $roleModel = new Roles(); 
        $roles = $roleModel->findAll();

        $data = [
        'pageTitle'=>'Add User',
        ];
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name; 
        $data['roles'] = $roles; 

        return view('backend/pages/new-user', $data); 
    }



    public function createUser() {
        $request = \Config\Services::request();
    
        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'full_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Full name is required',
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please provide a valid email address',
                        'is_unique' => 'This email is already registered'
                    ]
                ],
                'role' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Role is required',
                    ]
                ],
            ]);
    
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'errors' => $errors
                ]);
            } else {
                $userModel = new User();
    
                $data = [
                    'full_name' => $this->request->getPost('full_name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role'),
                ];
    
                if ($userModel->save($data)) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'User added successfully',
                        'token' => csrf_hash()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to add user. Please try again.',
                        'token' => csrf_hash()
                    ]);
                }
            }
        } else {
            return $this->response->setStatusCode(400, 'Bad Request');
        }
    }
    public function editUser($id)
{
    $userModel = new User();
    $roleModel = new Roles();

    $user = $userModel->find($id);
    $roles = $roleModel->findAll();

    return view('backend/user/edit_user', ['user' => $user, 'roles' => $roles]);
}

public function getUser($id)
{
    if ($id === null) {
        return $this->response->setJSON([
            'status' => 0,
            'msg' => 'User ID is missing.',
        ]);
    }

    $userModel = new User();
    $roleModel = new Roles();

    $user = $userModel->find($id);
    $roles = $roleModel->findAll();

    if ($user) {
        return $this->response->setJSON([
            'status' => 1,
            'user' => $user,
            'roles' => $roles,
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 0,
            'msg' => 'User not found',
        ]);
    }
}



public function updateUser()
{
    $request = \Config\Services::request();

    if ($request->isAJAX()) {
        // Retrieve user ID from the request
        $userId = $request->getVar('user_id');

        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required',
            'email' => 'required|valid_email',
            'role' => 'required',
        ]);

        // Run validation
        if (!$validation->withRequest($this->request)->run()) {
            // Validation failed, return errors
            $errors = $validation->getErrors();
            return $this->response->setJSON([
                'status' => 0,
                'errors' => $errors,
            ]);
        } else {
            // Validation passed, update user
            $userModel = new UserModel();
            $data = [
                'full_name' => $request->getVar('full_name'),
                'email' => $request->getVar('email'),
                'role' => $request->getVar('role'),
            ];

            // Perform the update operation
            if ($userModel->update($userId, $data)) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'User updated successfully',
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Failed to update user. Please try again.',
                ]);
            }
        }
    }
}



public function deleteUser($id)
{
    $userModel = new User();

    if ($userModel->delete($id)) {
        return $this->response->setJSON([
            'status' => 1,
            'msg' => 'User deleted successfully',
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 0,
            'msg' => 'Failed to delete user. Please try again.',
        ]);
    }
}



    public function addTicket(){
        $data = [
        'pageTitle'=>'Add Ticket',
        ];
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name; 
        return view('backend/pages/new-ticket', $data); 
    }

    public function createTicket(){
        $request = \Config\Services::request();
        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Subject is required',
                    ]
                    ],
                    'content' => [
                        'rules' => 'required|min_length[20]',
                        'errors' => [
                            'required' => 'Description is required',
                            'min_Length' => 'Subject must have atleast 20 characters'
                        ]
                        ],
             
            ]);
    
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => $errors
                ]);
            } else {
                $ticketModel = new Tickets();
                $user_id = CIAuth::id();

                $data = [
                    'subject' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('content'),
                    'user_id' =>$user_id,
                    'status'=> 'open',

                ];
    
                if ($ticketModel->save($data)) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'Ticket added successfully',
                        'token' => csrf_hash()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to add ticket. Please try again.',
                        'token' => csrf_hash()
                    ]);
                }
            }
        }

    }

    public function postReply()
    {
        $replyModel = new Replies();
        $user_id = CIAuth::id();
    
        $ticket_id = $this->request->getPost('ticket_id');
        $reply_content = $this->request->getPost('reply_content');
    
        if (!$ticket_id || !$reply_content) {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Missing ticket ID or reply content',
            ]);
        }
    
        $data = [
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'description' => $reply_content,
        ];
    
        if ($replyModel->save($data)) {
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Reply posted successfully',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to post reply',
            ]);
        }
    }

    public function dashboard()
    {
        $userModel = new User();
        $userId = CIAuth::id(); 
        $user = $userModel->find($userId);

        return view('backend/pages/home', ['user' => $user]);
    }
        
}
