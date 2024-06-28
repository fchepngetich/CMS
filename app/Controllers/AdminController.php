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
 
public function forgotPassword(){
$usermodel = new User();

}
    public function index()
    {
        $ticketModel = new Tickets();
        $repliesModel = new Replies();
        $userModel = new User();
        $full_name = CIAuth::fullName();

        $tickets = $ticketModel->orderBy('created_at', 'DESC')->findAll();
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
                $password = $this->generatePassword(8);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
                $data = [
                    'full_name' => $this->request->getPost('full_name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role'),
                    'password' => $hashedPassword, 
                ];
    
                if ($userModel->save($data)) {
                    $email = \Config\Services::email();
                    $email->setTo($data['email']);
                    $email->setSubject('CMS User Credentials');
                    $email->setMessage("You have been added as a user in the CMS system. Your password is: {$password}");
    
                    if ($email->send()) {
                        return $this->response->setJSON([
                            'status' => 1,
                            'msg' => 'User added successfully. Password has been sent to their email.',
                            'token' => csrf_hash()
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'status' => 1,
                            'msg' => 'User added successfully. Failed to send the password email.',
                            'token' => csrf_hash()
                        ]);
                    }
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
    
    private function generatePassword($length = 8) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialCharacters = '!@#$%^&*()-_+=<>?';
    
        $allCharacters = $uppercase . $lowercase . $numbers . $specialCharacters;
        $password = [
            $uppercase[rand(0, strlen($uppercase) - 1)],
            $lowercase[rand(0, strlen($lowercase) - 1)],
            $numbers[rand(0, strlen($numbers) - 1)],
            $specialCharacters[rand(0, strlen($specialCharacters) - 1)]
        ];
    
        for ($i = 4; $i < $length; $i++) {
            $password[] = $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }
    
        shuffle($password);
    
        return implode('', $password);
    }  

    public function getUser($id)
    {

       
    
        $userModel = new User();
        $roleModel = new Roles();
    
        $user = $userModel->find($id);
        $roles = $roleModel->findAll();
        foreach ($roles as $role) {
            echo $role['name']; // Assuming 'name' is the column name in your roles table
        }
    
        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[$role['id']] = $role['name'];
        }

    
        if ($user) {
            $user['role'] = isset($roleNames[$user['role']]) ? $roleNames[$user['role']] : 'Unknown Role';
            $user['name'] = getRoleNameById($user['role']); 
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
     
    public function roleName($roleId)
    {
        $roleModel = new Roles();

        $roleName = $roleModel->getRoleNameById($roleId);

        return $this->response->setJSON(['name' => $roleName]);
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
        //function to edit a user
        public function edit() {
            $userId = $this->request->getGet('id');
            $userModel = new User();
            $roleModel = new Roles(); 
        
            $user = $userModel->find($userId);
            $roles = $roleModel->findAll();
        
            if ($user) {
                return $this->response->setJSON(['status' => 1, 'data' => $user, 'roles' => $roles]);
            } else {
                return $this->response->setJSON(['status' => 0, 'msg' => 'User not found.']);
            }
        }
        
    //function to update a user
    public function update() {
            $userModel = new User();
            $userId = $this->request->getPost('user_id');
    
            $data = [
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role_id'),
            ];
    
            if ($userModel->update($userId, $data)) {
                return $this->response->setJSON(['status' => 1, 'msg' => 'User updated successfully.']);
            } else {
                return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to update user.']);
            }
        }
    //function to delete a user
    public function delete() {
            $userId = $this->request->getPost('id');
            $userModel = new User();
    
            if ($userModel->delete($userId)) {
                return $this->response->setJSON(['status' => 1, 'msg' => 'User deleted successfully.']);
            } else {
                return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to delete user.']);
            }
            
        }

        public function changePassword()
    {
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name; 
        return view('backend/pages/auth/change_password',$data);
    }
        public function updatePassword()
{
    $session = session();
    $userModel = new User();

    // Get input values
    $currentPassword = $this->request->getPost('current_password');
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $session->setFlashdata('fail', 'New password and confirm password do not match.');
        return redirect()->back();
    }

    $userId = $session->get('user_id'); // Assuming user_id is stored in session
    $user = $userModel->find($userId);

    // Debugging: Print the user data
    if (is_null($user)) {
        $session->setFlashdata('fail', 'User not found.');
        return redirect()->back();
    } else {
        // Check if the password field exists
        if (!isset($user['password'])) {
            $session->setFlashdata('fail', 'Password field not found in user data.');
            return redirect()->back();
        }
    }

    if (!password_verify($currentPassword, $user['password'])) {
        $session->setFlashdata('fail', 'Current password is incorrect.');
        return redirect()->back();
    }

    // Update password
    $userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);

    $session->setFlashdata('success', 'Password successfully updated.');
    return redirect()->to('backend/pages/auth/change_password');
}
public function profile()
{
    $session = session();
    $userModel = new User();
    $full_name = CIAuth::fullName(); 
    $userId = CIAuth::id();

    $user = $userModel->find($userId);

    if (is_null($user)) {
        $session->setFlashdata('fail', 'User not found.');
        return redirect()->to('backend/pages/auth/profile'); 
    }

    return view('backend/pages/auth/profile', [
        'user' => $user,
        'full_name' => $full_name
    ]);
}


    }
    
