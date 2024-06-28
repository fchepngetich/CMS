<?php
    function getRoleNameById($id)
    {
        $roleModel = new \App\Models\Roles();
        $role = $roleModel->find($id);

        return $role ? $role['name'] : null;
    }

    function getUsernameById($id)
    {
        $userModel = new \App\Models\User();
        $user = $userModel->find($id);

        return $user ? $user['full_name'] : null;
    }