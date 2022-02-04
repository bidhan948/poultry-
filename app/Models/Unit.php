<?php

namespace App\Models;

use CodeIgniter\Model;

class Unit extends Model
{
	
	protected $table                = 'units';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description'];
}
