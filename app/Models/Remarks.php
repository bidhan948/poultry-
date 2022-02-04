<?php

namespace App\Models;

use CodeIgniter\Model;

class Remarks extends Model
{
	
	protected $table                = 'remarkstypes';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description','unitId','status'];


	public function getAllReamrksTypeWithUnit() {
		$query = $this->db->query("SELECT b.id as id, b.name as name,b.status as status, b.description as description, pt.name as unitName, pt.id as unitId FROM remarkstypes b JOIN units pt ON b.unitId= pt.id");
		// your object result
		$result_object = $query->getResult();

		return $result_object;
	}
	public function getAllReamrksTypeWithUnitWithActiveStatus() {
		$query = $this->db->query("SELECT b.id as id, b.name as name, b.description as description, pt.name as unitName, pt.id as unitId FROM remarkstypes b JOIN units pt ON b.unitId= pt.id WHERE b.status = 1");
		// your object result
		$result_object = $query->getResult();

		return $result_object;
	}
}
