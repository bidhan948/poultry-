<?php

namespace App\Controllers;

use App\Models\ExtendedMainEntry;
use \App\Models\MainEntry;
use \App\Models\Shed;

class Entry extends BaseController
{
	public function mainEntry()
	{
		return view('entry/mainEntry');
	}
	public function mainEntryAdd()
	{
		return view('entry/mainEntryAdd');
	}
	public function mainEntryUpdate($id = null)
	{
		$mainEntryModel = new MainEntry();
		$extendedEntryModel = new ExtendedMainEntry();
		$shedModel = new Shed();
		$mainEntryData = $mainEntryModel->where('id', $id)->first();
		$shedData = $shedModel->where('id', $mainEntryData->shedId)->first();
		$data['mainEntry'] = $mainEntryData;
		$data['shed'] = $shedData;
		$data['extendedMainEntry'] =  $extendedEntryModel->where('mainEntryId',$mainEntryData->id)->get()->getResult();
		return view('entry/mainEntryUpdate', $data);
	}
	
	public function dailyEntryAdd()
	{
		return view('entry/dailyEntryAdd');
	}
	public function dailyEntry()
	{
		return view('entry/dailyEntry');
	}
	public function dailyEntryDetail($id = null)
	{
		return view('entry/dailyEntryDetail');
	}
}
