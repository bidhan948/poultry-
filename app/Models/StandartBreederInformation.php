<?php

namespace App\Models;

use CodeIgniter\Model;

class StandartBreederInformation extends Model
{

    protected $table                = 'standardbreederinformations';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'ageInWeeks',
        'hatchabilityWeekly',
        'hatchabilityCum',
        'fertilityWeekly',
        'fertilityCum',
        'hatchOfFertilesWeekly',
        'hatchOfFertilesCum',
        'chickNoHenHousedWeekly',
        'chickNoHenHousedCum',
        'chickWeightGram',
    ];
}