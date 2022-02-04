<?php

namespace App\Controllers;

class Setting extends BaseController
{
	public function shed()
	{
		return view('setting/shed');
	}
	public function group()
	{
		return view('setting/group');
	}
	public function feedType()
	{
		return view('setting/feedType');
	}
	public function poultryType()
	{
		return view('setting/poultryType');
	}
	public function breed()
	{
		return view('setting/breed');
	}
	public function unit()
	{
		return view('setting/unit');
	}
	public function remarks()
	{
		return view('setting/remarks');
	}
	public function medicineVaccine()
	{
		return view('setting/medicineVaccine');
	}
	public function standardBreederInformation()
	{
		return view('setting/standardBreederInformation');
	}
	public function standardBreederPerformances()
	{
		return view('setting/standardBreederPerformances');
	}
	public function standardHatcheryInformation()
	{
		return view('setting/standardHatcheryInformation');
	}
}