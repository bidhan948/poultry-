<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyMedicineVaccine extends Model
{
	
	protected $table                = 'dailymedicinevaccines';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = [
		'medicinevaccineId',
		'dailyEntryId',
		'quantity',
	];


}
