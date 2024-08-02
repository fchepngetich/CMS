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
 

use App\Models\Logs;

if (!function_exists('log_action')) {
    function log_action($userId, $message)
    {
        $logModel = new Logs();
        $logData = [
            'user_id' => $userId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $logModel->insert($logData);
    }
}
