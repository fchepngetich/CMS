<?php
if (!function_exists('getRoleNameById')) {
    function getRoleNameById($roleId)
    {
        $roleModel = new \App\Models\Roles(); 
        return $roleModel->getRoleNameById($roleId);
    }
}
