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
use App\Models\Categories;

use \Mberecall\CI_Slugify\SlugService;
use SSP;


class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $db;
    public function __construct()
    {
        require_once APPPATH . 'ThirdParty/ssp.php';
        $this->db = db_connect();
    }

    public function forgotPassword()
    {
        $usermodel = new User();

    }
    public function default(){
        
        return redirect()->to(base_url('/admin/login'));
    }
    
    public function index()
{
    $ticketModel = new Tickets();
    $full_name = CIAuth::fullName();
    $userId = CIAuth::id();
    $categoryModel = new Categories();
    $categories = $categoryModel->orderBy('unread_count', 'DESC')->orderBy('last_ticket_id', 'DESC')->findAll();

    $data['full_name'] = $full_name;
    $data['categories'] = $categories;


    foreach ($data['categories'] as &$category) {
        $category['total_tickets'] = $ticketModel->where('category_id', $category['id'])->countAllResults();
        $category['pending_tickets'] = $ticketModel->where(['category_id' => $category['id'], 'status' => 'open'])->countAllResults();
        $category['closed_tickets'] = $ticketModel->where(['category_id' => $category['id'], 'status' => 'closed'])->countAllResults();
    }

    return view('backend/pages/home', $data);
}
    
    
    /*
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
            'full_name' => $full_name
        ]);
    }*/
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
    
   public function search()
{
    $title = $this->request->getPost('title');
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');
    $status = $this->request->getPost('status');

    $ticketModel = new Tickets();
    $repliesModel = new Replies();
    $userModel = new User();
    $full_name = CIAuth::fullName();
    $loggedInUserId = CIAuth::id();

    $replies = $repliesModel->findAll();

    $query = $ticketModel->select('*');

    if (!empty($title)) {
        $query->like('subject', $title);
    }

    if (!empty($start_date)) {
        $query->where('created_at >=', $start_date . ' 00:00:00');
    }

    if (!empty($end_date)) {
        $query->where('created_at <=', $end_date . ' 23:59:59');
    }

    if (!empty($status)) {
        $query->where('status', $status);
    }

    $tickets = $query->findAll();

    return view('backend/pages/home', [
        'tickets' => $tickets,
        'replies' => $replies,
        'userModel' => $userModel,
        'full_name' => $full_name,
        'subject' => $title,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => $status
    ]);
}
 
public function searchTicketsInCategories()
{
    $request = \Config\Services::request();
    $ticketModel = new Tickets();
    $categoryModel = new Categories();
    
    $replyModel = new Replies();

    $replies = $replyModel->findAll();
    $full_name = CIAuth::fullName();
    
    $title = $request->getPost('title');
    $startDate = $request->getPost('start_date');
    $endDate = $request->getPost('end_date');
    $status = $request->getPost('status');
    $categoryId = $request->getPost('category_id');
    
    $data['category'] = $categoryModel->find($categoryId);
    if (!$data['category']) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
    }
    
    $query = $ticketModel->where('category_id', $categoryId);

    if ($title) {
        $query->like('subject', $title);
    }

    if ($startDate) {
        $query->where('created_at >=', $startDate);
    }

    if ($endDate) {
        $query->where('created_at <=', $endDate);
    }

    if ($status) {
        $query->where('status', $status);
    }

    $data['tickets'] = $query->findAll();
    
    // Pass search parameters to the view to maintain the form state
    $data['title'] = $title;
    $data['start_date'] = $startDate;
    $data['end_date'] = $endDate;
    $data['status'] = $status;
    $data['category_id'] = $categoryId;
    $data['replies'] = $replies;
    $data['full_name'] = $full_name;


    
    return view('backend/pages/categories/tickets', $data);
}



   public function searchMyTickets()
{
    $title = $this->request->getPost('title');
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');
    $status = $this->request->getPost('status');

    $ticketModel = new Tickets();
    $repliesModel = new Replies();
    $userModel = new User();
    $full_name = CIAuth::fullName();
    $loggedInUserId = CIAuth::id();

    $replies = $repliesModel->findAll();

$loggedInUserId = CIAuth::id();


$query = $ticketModel->select('*')->where('user_id', $loggedInUserId);

    if (!empty($title)) {
        $query->like('subject', $title);
    }

    if (!empty($start_date)) {
        $query->where('created_at >=', $start_date . ' 00:00:00');
    }

    if (!empty($end_date)) {
        $query->where('created_at <=', $end_date . ' 23:59:59');
    }

    if (!empty($status)) {
        $query->where('status', $status);
    }

    $tickets = $query->findAll();

    return view('backend/pages/my-tickets', [
        'tickets' => $tickets,
        'replies' => $replies,
        'userModel' => $userModel,
        'full_name' => $full_name,
        'subject' => $title,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => $status
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
            'full_name' => $full_name

        ]);
    }
   public function logoutHandler()
{
    CIAuth::forget();
    return redirect()->to(base_url('admin/login'))->with('fail', 'You are logged out');
}


    public function tickets()
    {
        $ticketModel = new Tickets();
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name;

        $data['tickets'] = $ticketModel->findAll();
        return view('backend/pages/home', $data);
    }
    
    public function viewTicketsByCategory($categoryId)
{
    $ticketModel = new Tickets();
    $categoryModel = new Categories();
    $replyModel = new Replies();

    $replies = $replyModel->findAll();
   

    $data['category'] = $categoryModel->find($categoryId);
    
    if (!$data['category']) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
    }
    $ticketModel->where('category_id', $categoryId)->set('read_status', 1)->update();

    $categoryModel->update($categoryId, ['unread_count' => 0]);


    $data['tickets'] = $ticketModel->where('category_id', $categoryId)->findAll();
    $data['full_name'] = CIAuth::fullName();
    $data['replies'] = $replies;

    return view('backend/pages/categories/tickets', $data);
}

    
    public function viewTicket($id)
{
    $ticketModel = new \App\Models\Tickets();
    $replyModel = new \App\Models\Replies();
    $userModel = new User();
    $full_name = CIAuth::fullName();



    $ticket = $ticketModel->find($id);
    $replies = $replyModel->where('ticket_id', $id)->findAll();

    if (!$ticket) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    return view('backend/pages/ticket-details', [
        'ticket' => $ticket,
        'replies' => $replies,
        'userModel' => $userModel,
        'full_name' => $full_name


    ]);
}

    public function getUsers()
    {
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


    public function addUser()
    {
        $roleModel = new Roles();
        $roles = $roleModel->findAll();

        $data = [
            'pageTitle' => 'Add User',
        ];
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name;
        $data['roles'] = $roles;

        return view('backend/pages/new-user', $data);
    }

    
public function createUser()
{
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
                'rules' => 'required|valid_email|is_unique[users.email]|regex_match[/^[\w\.\-]+@zetech\.ac\.ke$/]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please provide a valid email address',
                    'is_unique' => 'This email is already registered',
                    'regex_match' => 'Email must be a zetech.ac.ke email address',
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
                $userId = \App\Libraries\CIAuth::id(); 
                $message = "User Full name: {$data['full_name']} with email: {$data['email']} was created.";
                log_action($userId, $message);

                $email = \Config\Services::email();
                $email->setFrom('noreplyzetech@changemanagementsystem.com', 'Change Management System');
                $email->setTo($data['email']);
                $email->setCC('another@another-example.com'); // Optional
                $email->setBCC('them@their-example.com'); // Optional
                $email->setSubject('CMS User Credentials');

                $message = "
                    <html>
                    <head>
                        <title>CMS User Credentials</title>
                    </head>
                    <body>
                        <h2>Welcome to the Change Management System</h2>
                        <p>Dear {$data['full_name']},</p>
                        <p>You have been added as a user in the CMS system. Here are your credentials:</p>
                        <table>
                            <tr>
                                <td><strong>Username:</strong></td><td>{$data['email']}</td>
                            </tr>
                            <tr>
                                <td><strong>Password:</strong></td><td>{$password}</td>
                            </tr>
                            <tr>
                                <td><strong>Website Link:</strong></td><td><a href='https://demo.zetech.ac.ke/cms/admin/home'>Change Management System</a></td>
                            </tr>
                        </table>
                        <p>Please make sure to change your password after your first login.</p>
                        <br>
                        <p>Best Regards,</p>
                        <p>Change Management System Team</p>
                    </body>
                    </html>";

                $email->setMessage($message);
                $email->setMailType('html'); 

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
                    'token' => csrf_hash(),
                ]);
            }
        }
    } else {
        return $this->response->setStatusCode(400, 'Bad Request');
    }
}



    /*public function createUser()
    {
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
    }*/

    private function generatePassword($length = 8)
    {
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
            echo $role['name']; 
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

    public function addTicket()
    {
        $categoryModel = new Categories();
        $categories = $categoryModel->findAll(); 
        $data = [
            'pageTitle' => 'Add Ticket',
        ];
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name;
                $data['categories'] = $categories;

        return view('backend/pages/new-ticket', $data);
    }

 public function createTicket()
{
    $request = \Config\Services::request();

    if ($request->isAJAX()) {
        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required',
            'content' => 'required|min_length[3]',
            'category_id' => 'required|integer',
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
            $categoryModel = new Categories();
            $user_id = \App\Libraries\CIAuth::id();

            $data = [
                'subject' => $this->request->getPost('title'),
                'description' => $this->request->getPost('content'),
                'category_id' => $this->request->getPost('category_id'),
                'user_id' => $user_id,
                'status' => 'open',
                
            ];

            if ($ticketModel->save($data)) {
                $category_id = $this->request->getPost('category_id');

                $category = $categoryModel->find($category_id);
                if (!$category) {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Category not found for updating.',
                        'token' => csrf_hash()
                    ]);
                }

                $categoryData = [
                    'last_ticket_id' => $ticketModel->getInsertID(),
                    'unread_count' => $category['unread_count'] + 1
                ];

                if (!$categoryModel->update($category_id, $categoryData)) {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to update category unread count.',
                        'token' => csrf_hash()
                    ]);
                }

                $message = "Ticket: {$data['subject']} was created.";
                log_action($user_id, $message);

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Ticket added successfully',
                    'redirect' => base_url('admin/home'),
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
    public function edit()
    {
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

    public function update()
{
    $userModel = new User();
    $userId = $this->request->getPost('user_id');

    // Fetch current user data before update
    $currentUser = $userModel->find($userId);
    if (!$currentUser) {
        return $this->response->setJSON(['status' => 0, 'msg' => 'User not found.']);
    }

    // New data from form submission
    $newData = [
        'full_name' => $this->request->getPost('full_name'),
        'email' => $this->request->getPost('email'),
        'role' => $this->request->getPost('role_id'),
    ];

    // Compare old and new data
    $changes = [];
    foreach ($newData as $field => $value) {
        if ($currentUser[$field] != $value) {
            $changes[] = "{$field}: '{$currentUser[$field]}' to '{$value}'";
        }
    }

    // Perform the update
    if ($userModel->update($userId, $newData)) {
        // Retrieve current user's full name for logging
        $loggedInUserName = \App\Libraries\CIAuth::id();

        $username = getUsernameById($userId);

if (!empty($changes)) {
    $message = "User '{$username}' was updated.. Changes: " . implode(', ', $changes);
} else {
    $message = "User '{$username}' was updated without changes.";
}
        log_action($loggedInUserName, $message);

        return $this->response->setJSON(['status' => 1, 'msg' => 'User updated successfully.']);
    } else {
        return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to update user.']);
    }
}

    public function delete()
    {
        $userId = $this->request->getPost('id');
        $userModel = new User();
        $username = getUsernameById($userId);

        $loggedInId = \App\Libraries\CIAuth::id();

        if ($userModel->delete($userId)) {
            $message = "User '{$username}' was deleted.";
        log_action($loggedInId, $message);
            return $this->response->setJSON(['status' => 1, 'msg' => 'User deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to delete user.']);
        }

    }

    public function changePassword()
    {
        $full_name = CIAuth::fullName();

        $data['full_name'] = $full_name;
        return view('backend/pages/auth/change_password', $data);
    }
 public function updatePassword()
{
    $session = session();
    $userModel = new User();

    $currentPassword = $this->request->getPost('current_password');
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    // Password validation rules
    $validationRules = [
        'new_password' => [
            'rules' => 'required|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/]',
            'errors' => [
                'required' => 'New password is required.',
                'min_length' => 'New password must be at least 8 characters long.',
                'regex_match' => 'New password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            ]
        ],
        'confirm_password' => [
            'rules' => 'required|matches[new_password]',
            'errors' => [
                'required' => 'Confirm password is required.',
                'matches' => 'Confirm password does not match the new password.'
            ]
        ]
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $userId = CIAuth::id();
    $user = $userModel->find($userId);

    if (is_null($user)) {
        $session->setFlashdata('fail', 'User not found.');
        return redirect()->back();
    } elseif (!isset($user['password'])) {
        $session->setFlashdata('fail', 'Password field not found in user data.');
        return redirect()->back();
    }

    if (!password_verify($currentPassword, $user['password'])) {
        $session->setFlashdata('fail', 'Current password is incorrect.');
        return redirect()->back();
    }

    $userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT), 'password_reset_required' => 0]);

    $session->setFlashdata('success', 'Password successfully updated.');
    return redirect()->to(base_url('/admin/home'));
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
    
public function getCategories()
{
    $ticketModel = new Tickets();
    $full_name = CIAuth::fullName();
    $userId = CIAuth::id();
    $categoryModel = new Categories();
    $categories = $categoryModel->orderBy('unread_count', 'DESC')->orderBy('last_ticket_id', 'DESC')->findAll();

    $data['full_name'] = $full_name;
    $data['categories'] = $categories;


    foreach ($data['categories'] as &$category) {
        $category['total_tickets'] = $ticketModel->where('category_id', $category['id'])->countAllResults();
        $category['pending_tickets'] = $ticketModel->where(['category_id' => $category['id'], 'status' => 'open'])->countAllResults();
        $category['closed_tickets'] = $ticketModel->where(['category_id' => $category['id'], 'status' => 'closed'])->countAllResults();
    }

    return view('backend/pages/categories/view', $data);
}

 public function categories()
    {
         $full_name = CIAuth::fullName();
        $userId = CIAuth::id();
        $categoryModel = new Categories();
        $data['full_name'] = $full_name;
        $data['categories'] = $categoryModel->findAll();
        
        return view('backend/pages/categories/add', $data);
    }
 
public function addCategory()
{
    $request = \Config\Services::request();

    if ($request->isAJAX()) {
        $validation = \Config\Services::validation();

        $validation->setRules([
             'name' => [
                'rules' => 'required|is_unique[categories.name]',
                'errors' => [
                    'required' => 'Category name is required',
                    'is_unique' => 'Category name already exists',
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Category description is required',
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
            $categoryModel = new Categories();

            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description')
            ];

            if ($categoryModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Category added successfully.',
                    'redirect' => base_url('admin/categories/get-categories'),
                    'token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Failed to add category. Please try again.',
                    'token' => csrf_hash()
                ]);
            }
        }
    } else {
        return $this->response->setStatusCode(400, 'Bad Request');
    }
}

public function editCategory($id)
{
    $categoryModel = new Categories();
    $category = $categoryModel->find($id);
    $full_name = CIAuth::fullName();

    if (!$category) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
    }

    return view('backend/pages/categories/edit', [
        'category' => $category,
        'full_name' => $full_name
    ]);
}


public function updateCategory($id)
{
    $request = \Config\Services::request();

    if ($request->isAJAX()) {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Category name is required',
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Category description is required',
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
            $categoryModel = new Categories();

            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description')
            ];

            if ($categoryModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Category updated successfully.',
                    'redirect' => base_url('admin/get-categories'),
                    'token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Failed to update category. Please try again.',
                    'token' => csrf_hash()
                ]);
            }
        }
    } else {
        return $this->response->setStatusCode(400, 'Bad Request');
    }
}

public function deleteCategory()
{
    $request = \Config\Services::request();
    $categoryId = $request->getPost('id');

    $categoryModel = new Categories();

    if ($categoryModel->find($categoryId)) {
        // Check if the category exists
        if ($categoryModel->where('id', $categoryId)->delete()) {
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Category deleted successfully.',
                'token' => csrf_hash()
            ]);
        } else {
            // Debug: Check why the delete method failed
            $db = \Config\Database::connect();
            $query = $db->getLastQuery();
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to delete category. Query: ' . $query,
                'token' => csrf_hash()
            ]);
        }
    } else {
        // Debug: Check if the category was not found
        return $this->response->setJSON([
            'status' => 0,
            'msg' => 'Category not found. ID: ' . $categoryId,
            'token' => csrf_hash()
        ]);
    }
}








}

