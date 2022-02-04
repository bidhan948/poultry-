<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedType extends Model
{
	
	protected $table                = 'feedtypes';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = ['name','description'];
}
