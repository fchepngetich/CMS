<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;

class AuthController extends BaseController
{

    protected $helpers = ['url', 'form'];
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }


 public function loginHandler()
{
    $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'full_name';

    $validationRules = [
        'login_id' => [
            'rules' => 'required|is_not_unique[users.' . $fieldType . ']',
            'errors' => [
                'required' => ($fieldType == 'email') ? 'Email is required' : 'Full Name is required',
                'is_not_unique' => ($fieldType == 'email') ? 'Email does not exist in the system' : 'Full Name does not exist in the system',
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[5]|max_length[45]',
            'errors' => [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 5 characters long',
                'max_length' => 'Password must not be longer than 45 characters',
            ]
        ]
    ];

    if (!$this->validate($validationRules)) {
        return view('backend/pages/auth/login', [
            'pageTitle' => 'Login',
            'validation' => $this->validator
        ]);
    } else {
        $user = new User();
        $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();

        $check_password = HASH::check($this->request->getVar('password'), $userInfo['password']);
        if (!$check_password) {
            return redirect()->to(base_url('admin/login'))->with('fail', 'Wrong password')->withInput();
        } else {
            CIAuth::CIAuth($userInfo);

            if ($userInfo['password_reset_required']) {
                return redirect()->to(base_url('admin/change-password'))->with('info', 'Please change your password on first login.');
            }

            return redirect()->to(base_url('admin/home'));
        }
    }
}



    public function forgotPassword()
    {
        $data = array(
            'pageTitle' => 'Forgot Password',

        );
        return view('backend/pages/auth/forgot', $data);
    }

    public function sendResetLink()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please provide a valid email address'
                ]
            ],

        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('fail', $validation->getError('email'));
        }

        $email = $this->request->getPost('email');
        $userModel = new User();

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('fail', 'This email is not registered');
        }

        $newPassword = $this->generatePassword();
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $userModel->update($user['id'], ['password' => $hashedPassword]);
        $username='User';

        $this->sendEmail($email,$username, $newPassword);
        return redirect()->route('admin.login.form')->with('success', 'A new password has been sent to your email address.');
    }

    private function generatePassword()
    {
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $charactersLength = strlen($characters);
        $randomString = '';
        $randomString .= $characters[rand(0, 9)];
        $randomString .= $characters[rand(10, 35)];
        $randomString .= $characters[rand(36, 61)];
        $randomString .= $characters[rand(62, strlen($characters) - 1)];

        for ($i = 4; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return str_shuffle($randomString);
    }

    private function sendEmail($to, $username, $newPassword)
{
    $email = \Config\Services::email();

    $email->setFrom('your@example.com', 'Change Management System');
    $email->setTo($to);
    $email->setSubject('Password Reset');

    $message = "
        <html>
        <head>
            <title>Password Reset</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    background-color: #f9f9f9;
                }
                .header {
                    text-align: center;
                    padding: 10px 0;
                }
                .content {
                    margin-top: 20px;
                }
                .content p {
                    margin: 10px 0;
                }
                .content a {
                    color: #007bff;
                    text-decoration: none;
                }
                .content a:hover {
                    text-decoration: underline;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 0.9em;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Change Management System</h2>
                </div>
                <div class='content'>
                    <p>Dear {$username},</p>
                    <p>Your password has been reset. Here are your new credentials:</p>
                    <p><strong>Username:</strong> {$to}</p>
                    <p><strong>Password:</strong> {$newPassword}</p>
                    <p>You can log in to the system using the following link:</p>
                    <p><a href='https://demo.zetech.ac.ke/cms/admin/home'>Change Management System</a></p>
                    <p>Please make sure to change your password after logging in for the first time.</p>
                </div>
                <div class='footer'>
                    <p>Best Regards,</p>
                    <p>Change Management System Team</p>
                </div>
            </div>
        </body>
        </html>
    ";

    $email->setMessage($message);
    $email->setMailType('html'); 

    if (!$email->send()) {
        log_message('error', 'Failed to send password reset email to ' . $to);
    }
}



    public function changePassword()
    {
        $data = array(
            'pageTitle' => 'Change Password',

        );
        return view('backend/pages/auth/change_password', $data);
    }


   
}



