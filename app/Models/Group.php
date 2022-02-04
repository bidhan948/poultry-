<?php

namespace App\Models;

use CodeIgniter\Model;

class Group extends Model
{

	protected $table                = 'groups';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name', 'description'];



	public function getLot($id)
	{
		$shedQuery = "SELECT * FROM sheds WHERE groupId ='".$id."'";
		$shedDataQuery = $this->db->query($shedQuery);
		$shedDataObject =  $shedDataQuery->getResult();
		
		foreach ($shedDataObject as $key => $shed) {
			$lotQuery = "SELECT lot,male,female,totalEggProduction FROM dailyentries WHERE shedId = '".$shed->id."' ORDER BY id DESC LIMIT 1";
			$lotDataObject = $this->db->query($lotQuery);
			$lotDataObject = $lotDataObject->getResult();
			$shedDataObject[$key]->lot = $lotDataObject;
		}
		
		return $shedDataObject;
	}
}
