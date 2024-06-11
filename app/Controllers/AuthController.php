<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;

class AuthController extends BaseController
{
protected $helpers = ['url','form'];
    public function loginForm(){
        $data = [
            'pageTitle' =>'Login',
            'validation'=>null
        ];
        return view('backend/pages/auth/login',$data);
    }

    public function loginHandler(){
        $fieldType = filter_var($this->request->getVar('login_id'),FILTER_VALIDATE_EMAIL) ? 'email' : 'full_name';
        if($fieldType == 'email'){
            $isValid = $this->validate([
                'login_id' =>[
                    'rules' =>'required|valid_email|is_not_unique[users.email]',
                    'errors' =>[
                        'required' =>'Email is required',
                        'valid_email' =>'Please Enter a valid Email' ,
                        'is_not_unique' =>'Email does not exist in the system',  

                    ]
                    
                ],
            'password' =>[
                'rules' =>'required|min_length[5]|max_length[45]',
                'errors'=>[
                    'required'=>'Password is required',
                    'min_length'=>'Password must be at least 5 characters long',
                    'max-length'=>'Password must not be longer trhan 45 characters'
                ]
            ]
            ]);
        }else{
            $isValid = $this->validate([
                'login_id' =>[
                    'rules' =>'required|is_not_unique[users.full_name]',
                    'errors' =>[
                        'required' =>'Full Name is required',
                        'is_not_unique' =>'Full Name does not exist in the system'  

                    ]

                ],
                'password' =>[
                    'rules' =>'required|min_length[5]|max_length[45]',
                    'errors'=>[
                        'required'=>'Password is required',
                        'min_length'=>'Password must be at least 5 characters long',
                        'max-length'=>'Password must not be longer trhan 45 characters'


                    ]
                ]
            ]);

        }
        if(!$isValid){
            return view('backend/pages/auth/login',[
                'pageTitle'=>'Login',
                'validation'=>$this->validator
            ]);
        }else{
            $user = new User();
            $userInfo =$user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = HASH::check($this->request->getVar('password'),$userInfo['password']);
            if(!$check_password){
                return redirect()->route('admin.login.form')->with('fail','wrong password')->withInput();
            }else{
                CIAuth::CIAuth($userInfo);
                return redirect()->route('admin.home');

            }
        }
    }
    public function forgotForm(){
        $data =array(
            'pageTitle'=>'Forgot Password',
            'validation'=>null,
        );
        return view('backend/pages/auth/forgot',$data);
    }
    public function sendPasswordResetLink(){
        echo 'Send Password Reset Link';
    }
}
