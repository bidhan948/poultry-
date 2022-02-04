<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineVaccine extends Model
{
	
	protected $table                = 'medicinevaccines';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','physicalForm','type','description'];
}
