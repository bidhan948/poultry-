<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyRemark extends Model
{
	
	protected $table                = 'dailyremarks';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = [
		'remarkId',
		'dailyEntryId',
		'quantity',
	];


}
