<?php

namespace App\Models;

use CodeIgniter\Model;

class Shed extends Model
{
	
	protected $table                = 'sheds';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description','groupId'];


	public function getAllShedsWithGroup() {
		$query = $this->db->query("SELECT s.id as id, s.name as name, s.description as description, g.name as groupName, g.id as groupId FROM sheds s JOIN groups g ON s.groupId= g.id");
		// your object result
		$result_object = $query->getResult();

		return $result_object;
	}
}
