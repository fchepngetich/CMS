<?php
/*
namespace App\Controllers;

use App\Models\Roles;
use App\Libraries\CIAuth;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RolesController extends BaseController
{
    public function index()
{
    $roleModel = new Roles();
    $roles = $roleModel->findAll();
    $full_name = CIAuth::fullName();


    return view('backend/pages/roles', ['roles' => $roles,'full_name'=>$full_name]);
}

public function create()
{
    if ($this->request->getMethod() === 'post') {
        $roleModel = new Roles();

        // Validate form input
        $validationRules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'description' => 'max_length[255]'
        ];

        if ($this->validate($validationRules)) {
            $roleData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description')
            ];

            if ($roleModel->save($roleData)) {
                return redirect()->to('/roles')->with('success', 'Role created successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create role.');
            }
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    return view('backend/pages/roles');
}
public function edit($id)
{
    $roleModel = new Roles();
    $role = $roleModel->find($id);

    if ($role) {
        if ($this->request->getMethod() === 'post') {
            // Validate form input
            $validationRules = [
                'name' => 'required|min_length[3]|max_length[50]',
                'description' => 'max_length[255]'
            ];

            if ($this->validate($validationRules)) {
                $roleData = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description')
                ];

                // Update role in the database
                if ($roleModel->update($id, $roleData)) {
                    return redirect()->to('/roles')->with('success', 'Role updated successfully.');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Failed to update role.');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('backend/pages/roles/edit', ['role' => $role]);
    } else {
        return redirect()->to('/roles')->with('error', 'Role not found.');
    }
}


public function delete($id)
{
    $roleModel = new Roles();
    $role = $roleModel->find($id);

    if ($role) {
        // Delete role from the database
        if ($roleModel->delete($id)) {
            return redirect()->to('/roles')->with('success', 'Role deleted successfully.');
        } else {
            return redirect()->to('/roles')->with('error', 'Failed to delete role.');
        }
    } else {
        return redirect()->to('/roles')->with('error', 'Role not found.');
    }
}

public function getRoles()
{
    $roleModel = new Roles();

    // Get the DataTables request parameters
    $request = \Config\Services::request();
    $start = $request->getGet('start');
    $length = $request->getGet('length');
    $searchValue = $request->getGet('search')['value'];

    // Get total count of roles
    $totalRoles = $roleModel->countAll();

    // Apply search if necessary
    if (!empty($searchValue)) {
        $roleModel->like('name', $searchValue);
        $roleModel->orLike('description', $searchValue);
    }

    // Get filtered count of roles
    $filteredRoles = $roleModel->countAllResults(false);

    // Get roles with limit and offset
    $roles = $roleModel->findAll($length, $start);

    // Prepare response
    $data = [
        'draw' => intval($request->getGet('draw')),
        'recordsTotal' => $totalRoles,
        'recordsFiltered' => $filteredRoles,
        'data' => $roles
    ];

    return $this->response->setJSON($data);
}



    
}*/


namespace App\Controllers;

use App\Models\Roles;
use App\Libraries\CIAuth;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RolesController extends BaseController
{
    public function index()
    {
        $model = new RolesController();
        $data['roles'] = $model->findAll();
        return view('user_roles/index', $data);
    }

    public function create()
    {
        return view('user_roles/create');
    }

    public function store()
    {
        $model = new UserRoleModel();

        $data = [
            'role_name' => $this->request->getPost('role_name'),
        ];

        $model->save($data);

        return redirect()->to('/userroles');
    }

    public function edit($id = null)
    {
        $model = new UserRoleModel();
        $data['role'] = $model->find($id);

        return view('user_roles/edit', $data);
    }

    public function update($id = null)
    {
        $model = new UserRoleModel();

        $data = [
            'role_name' => $this->request->getPost('role_name'),
        ];

        $model->update($id, $data);

        return redirect()->to('/userroles');
    }

    public function delete($id = null)
    {
        $model = new UserRoleModel();
        $model->delete($id);

        return redirect()->to('/userroles');
    }
}
