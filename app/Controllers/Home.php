<?php

namespace App\Controllers;

use App\Models\Group;

class Home extends BaseController
{
	public function index()
	{
		$model = new Group();
		$data['groups'] = $model->findAll();
		return view('welcome_message', $data);
	}

	public function getGroupDetail($id)
	{
		$data['mainData'] = (new Group())->getLot($id);
		return view('summary_report', $data);
	}

	public function getShedData($id)
	{
		$data['id'] = $id;
		return view('summary_report_detail', $data);
	}
}
