<?php

namespace App\Models;

use CodeIgniter\Model;

class StandardBreederPerformance extends Model
{

    protected $table                = 'standardbreederperformances';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'ageInWeeks',
        'totalEggsPercentageHw',
        'hatchingEggsPercentageHw',
        'mortalityCumPercentage',
        'percentageHeWeekly',
        'totalEggsHh',
        'hatchingEggsHh',
        'hhhe',
        'henHouseNumber',
        'feedConversionRatio'
    ];
}
