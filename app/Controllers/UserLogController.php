<?php

namespace App\Controllers;

use App\Models\Shed;
use App\Models\UserLog;

class UserLogController extends BaseController
{
    public function index()
    {
        // if (!session()->get('role')) {
        //     return redirect()->to(base_url() . '/login');
        // }
        $model = new UserLog();
        $shedModel = new Shed();
        $data['userlogs'] = $model->findAll();
        $data['sheds'] = $shedModel->findAll();
        return view('user_log', $data);
    }
}
