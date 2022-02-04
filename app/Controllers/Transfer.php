<?php

namespace App\Controllers;

use App\Models\Shed;
use App\Models\StockTransfer;
use App\Models\StockTransferDetail;

class Transfer extends BaseController
{
	public function transfer()
	{
		return view('transfer/transfer');
	}
	public function transferAdd($id = null)
	{
		$shedModel = new Shed();
		$data['sheds'] = $shedModel->findAll();
		return view('transfer/transferAddC',$data);
	}
	// public function transferAdd($id = null)
	// {
	// 	return view('transfer/transferAddC');
	// }

	public function transferUpdate($id)
	{
		$stocktransfermodel = new StockTransfer();
		$stocktransferdetailModel = new StockTransferDetail();
		$shedModel = new Shed();
		$data['stockTransfer'] = $stocktransfermodel->where('id',$id)->first();
		$data['stockTransferDetail'] = $stocktransferdetailModel->where('stockTransferId',$id)->first();
		$data['sheds']  = $shedModel->findAll();
		return view('transfer/transferUpdate',$data);
	}
}
