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
        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please Enter a valid Email',
                        'is_not_unique' => 'Email does not exist in the system',

                    ]

                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 5 characters long',
                        'max-length' => 'Password must not be longer trhan 45 characters'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.full_name]',
                    'errors' => [
                        'required' => 'Full Name is required',
                        'is_not_unique' => 'Full Name does not exist in the system'

                    ]

                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 5 characters long',
                        'max-length' => 'Password must not be longer trhan 45 characters'


                    ]
                ]
            ]);

        }
        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        } else {
            $user = new User();
            $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = HASH::check($this->request->getVar('password'), $userInfo['password']);
            if (!$check_password) {
                return redirect()->route('admin.login.form')->with('fail', 'wrong password')->withInput();
            } else {
                CIAuth::CIAuth($userInfo);
                return redirect()->route('admin.home');

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

        $this->sendEmail($email, $newPassword);
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

    private function sendEmail($to, $newPassword)
    {
        $email = \Config\Services::email();

        $email->setFrom('your@example.com', 'Change Management System');
        $email->setTo($to);
        $email->setSubject('Password Reset');
        $email->setMessage('Your new password is: ' . $newPassword);

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



