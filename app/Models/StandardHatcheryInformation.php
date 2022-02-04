<?php

namespace App\Models;

use CodeIgniter\Model;

class StandardHatcheryInformation extends Model
{

    protected $table                = 'standardhatcheryinformations';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'ageInWeeks',
        'fertilityPercentage',
        'hatchabilityPercentage',
        'embInfertilePercentage',
        'embEarlyPercentage',
        'embMidPercentage',
        'embLatePercentage',
        'hofPercentage'
    ];
}