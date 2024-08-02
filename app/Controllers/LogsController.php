<?php
namespace App\Controllers;
use App\Models\Logs;
use App\Libraries\CIAuth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LogsController extends BaseController
{
    public function index()
    {
        $full_name = CIAuth::fullName();
        $data['full_name']= $full_name;
        $logModel = new Logs();
        $data['logs'] = $logModel->findAll();

        return view('backend/pages/logs/index', $data);
    }
}
