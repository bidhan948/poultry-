<?php

namespace App\Controllers;

use \App\Models\MainEntry;
use \App\Models\Shed;

class Excel extends BaseController
{
	public function dailyEntry()
	{
		return view('excel/dailyExcelEntry');
	}
}
