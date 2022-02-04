<?php

namespace App\Models;

use CodeIgniter\Model;


class UserLog extends Model
{

    protected $table = 'userlogs';

    protected $allowedFields = [
        'date',
        'transferFrom',
        'transferTo',
        'male',
        'female',
        'user',
        'fromLot',
        'toLot',
        'stockMale',
        'stockFemale',
        'action',
        'entryMale',
        'entryFemale',
        'entryShedId'
    ];
}
