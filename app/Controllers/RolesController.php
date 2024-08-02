<?php

namespace App\Controllers;

use App\Models\Roles;
use App\Libraries\CIAuth;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;


class RolesController extends BaseController

{
    
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new Roles();
    }

    public function index()
    {
        $roleModel = new Roles();
        $roles = $roleModel->findAll();
        $full_name = CIAuth::fullName();
        
        $data['roles'] = $roles;
        $data['full_name'] = $full_name;
        return view('backend/pages/user_roles/index',$data );
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'rules' => 'required|is_unique[roles.name]',
                'errors' => [
                    'required' => 'Role name is required',
                    'is_unique' => 'This role name already exists'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'errors' => $errors
            ]);
        } else {
            $this->roleModel->save(['name' => $this->request->getPost('name')]);
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Role created successfully',
                'token' => csrf_hash()
            ]);
        }
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        return $this->response->setJSON($role);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'rules' => 'required|is_unique[roles.name,id,' . $id . ']',
                'errors' => [
                    'required' => 'Role name is required',
                    'is_unique' => 'This role name already exists'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'errors' => $errors
            ]);
        } else {
            $this->roleModel->update($id, ['name' => $this->request->getPost('name')]);
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Role updated successfully',
                'token' => csrf_hash()
            ]);
        }
    }

    public function delete($id)
    {
        $this->roleModel->delete($id);
        return $this->response->setJSON([
            'status' => 1,
            'msg' => 'Role deleted successfully',
            'token' => csrf_hash()
        ]);
    }
}
