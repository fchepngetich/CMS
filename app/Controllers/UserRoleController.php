<?php

namespace App\Controllers;

use App\Models\Roles;
use CodeIgniter\Controller;
use App\Libraries\CIAuth;

class UserRoleController extends Controller
{
    public function index()
    {
        $full_name = CIAuth::fullName();
    
        $model = new Roles();
        $data['roles'] = $model->findAll();
        $data['full_name'] = $full_name;
        return view('backend/pages/user_roles/index', $data);
    }
    
    public function create()
    {
        $full_name = CIAuth::fullName();
        $data['full_name'] = $full_name;


        return view('backend/pages/user_roles/create',$data);
    }

    public function store()
    {
        $model = new Roles();

        $data = [
            'name' => $this->request->getPost('role_name'),
        ];

        $model->save($data);

        return redirect()->to('/userroles');
    }

    public function edit($id = null)
    {
        $model = new Roles();
        $full_name = CIAuth::fullName();
        $data['full_name'] = $full_name;
        $data['role'] = $model->find($id);

        return view('backend/pages/user_roles/edit', $data);
    }

    public function update($id = null)
    {
        $model = new Roles();

        $data = [
            'name' => $this->request->getPost('role_name'),
        ];

        $model->update($id, $data);

        return redirect()->to('/userroles');
    }

    public function delete($id = null)
    {
        $model = new Roles();
        $model->delete($id);

        return redirect()->to('/userroles');
    }
}

