<?php

namespace App\Controllers;

class Appstock extends BaseController
{
	public function totalStock()
	{
		return view('stock/totalStock');
	}
}
