<?php

namespace App\Models;

use CodeIgniter\Model;

class Breed extends Model
{
	
	protected $table                = 'breeds';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description','poultryTypeId'];


	public function getAllBreedsWithPoultryType() {
		$query = $this->db->query("SELECT b.id as id, b.name as name, b.description as description, pt.name as poultryTypeName, pt.id as poultryTypeId FROM breeds b JOIN poultrytypes pt ON b.poultryTypeId= pt.id");
		// your object result
		$result_object = $query->getResult();

		return $result_object;
	}
}
