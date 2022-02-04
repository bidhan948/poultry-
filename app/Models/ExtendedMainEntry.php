<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtendedMainEntry extends Model
{

    protected $table                = 'extendedmainentries';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'arrivalDate',
        'arrivalDateBs',
        'arrivalAge',
        'arrivalQuantityMale',
        'arrivalQuantityFemale',
        'breedTypeId',
        'mainEntryId'
    ];


    public function getExtendedEntriesByShedAndLot($shedId, $lot) {
        $queryString = "
        SELECT 
        SUM(eme.arrivalQuantityMale) as arrivalQuantityMale, 
        SUM(eme.arrivalQuantityFemale) as arrivalQuantityFemale, 
        eme.arrivalDate FROM extendedmainentries eme 
        INNER JOIN mainentries me ON eme.mainEntryId = me.id
        WHERE me.shedId = ".$shedId." AND me.lot = ".$lot."
        GROUP BY eme.arrivalDate 
        ";

        $dataQuery = $this->db->query($queryString);
		$data_object = $dataQuery->getResult();
        return $data_object;
    }
}
