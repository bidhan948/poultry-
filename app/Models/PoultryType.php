<?php

namespace App\Models;

use CodeIgniter\Model;

class PoultryType extends Model
{
	
	protected $table                = 'poultrytypes';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description'];
}
