<?php

use App\Models\Logs;

if (!function_exists('log_user_action')) {
    /**
     * Log user actions
     *
     * @param string $action The action performed (e.g., 'create', 'update', 'delete')
     * @param array $data The data associated with the action
     * @param int|null $userId The ID of the user performing the action, if applicable
     */
    function log_user_action($action, $data, $userId = null)
    {
        $logModel = new Logs();

        $logData = [
            'action'    => $action,
            'user_id'   => $userId,
            'data'      => json_encode($data),
            'created_at'=> date('Y-m-d H:i:s'),
        ];

        $logModel->insert($logData);
    }
}
