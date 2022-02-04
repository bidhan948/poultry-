<?php

namespace App\Models;

use CodeIgniter\Model;

class MainEntry extends Model
{

    protected $table                = 'mainentries';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'shedId',
        'lot',
        'arrivalDate',
        'arrivalDateBs',
        'arrivalAge',
        'arrivalQuantityMale',
        'arrivalQuantityFemale',
        'breedTypeId',
        'gender',
        'status',
        'description'
    ];


    public function getMainEntriesForList($pageIndex, $pageSize)
    {

        $queryDataString = "
        SELECT 
        me.id as id,
        me.shedId as shedId,
        s.name as shedName,
        s.description as shedDetails,
        me.lot as lot, 
        me.arrivalDate as arrivalDate,
        me.arrivalDateBs as arrivalDateBs,
        me.arrivalAge as arrivalAge,
        me.arrivalQuantityMale as arrivalQuantityMale,
        me.arrivalQuantityFemale as arrivalQuantityFemale,
        me.breedTypeId as breedTypeId,
        b.name as breedTypeName,
        me.gender as gender,
        me.status as status,
        me.description as description
        FROM mainentries me
        LEFT JOIN sheds s on me.shedId = s.id 
        LEFT JOIN breeds b on me.breedTypeId = b.id 
        ORDER BY me.arrivalDate DESC, me.shedId
        LIMIT " . $pageSize . " OFFSET " . ($pageIndex - 1) * $pageSize;

        $queryCountString = "
        SELECT 
        CEILING(Count(*)/" . $pageSize . ") as count
        FROM mainentries
        ";

        $dataQuery = $this->db->query($queryDataString);
        $countQuery = $this->db->query($queryCountString);
        // your object result
        $data_object = $dataQuery->getResult();
        $count_object = $countQuery->getResult();

        foreach ($data_object as $index => $item) {
           $subQuery ="SELECT 
                        arrivalDateBs, 
                        arrivalDate, 
                        DATEDIFF(arrivalDate,'".$item->arrivalDate."') + ".$item->arrivalAge." as arrivalAge,
                        arrivalQuantityMale, 
                        arrivalQuantityFemale
                        FROM extendedmainentries WHERE mainEntryId =". $item->id; 
           $subDataQuery = $this->db->query($subQuery);
           $sub_data_object = $subDataQuery->getResult();
           $data_object[$index]->extendedMainEntries = $sub_data_object;
        }
        // for extended table 

        $result = [
            'data'   => $data_object,
            'count'    => $count_object[0]->count,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
        ];

        return $result;
    }


    public function getShedDataFromEntriesByShed($shedId)
    {
        $queryDataString = "
        SELECT 
        me.lot as lot,
        me.breedTypeId as breedTypeId,
        s.description as shedDescription,
        me.arrivalAge as arrivalAge
        FROM mainentries me
        LEFT JOIN sheds s on me.shedId = s.id 
        WHERE (me.status = 0 OR me.status = 1) AND me.shedId =" . $shedId;

        $dataQuery = $this->db->query($queryDataString);
        $data_object = $dataQuery->getResult();

        return $data_object;
    }

    public function getRecentlyAddedMainEntryByShedIdAndLot($shedId, $lot)
    {
        $queryDataString = "
        SELECT 
        *
        FROM mainentries 
        WHERE status = 0 AND shedId =" . $shedId . " AND lot =" . $lot;

        $dataQuery = $this->db->query($queryDataString);
        $data_object = $dataQuery->getResult();

        return $data_object;
    }
    public function getRecentlyAddedOrActiveMainEntryByShedIdAndLot($shedId, $lot)
    {
        $queryDataString = "
        SELECT 
        *
        FROM mainentries 
        WHERE (status = 0 OR status = 1) AND shedId =" . $shedId . " AND lot =" . $lot;

        $dataQuery = $this->db->query($queryDataString);
        $data_object = $dataQuery->getResult();

        return $data_object;
    }
    public function getAllMainEntryByShedIdAndLot($shedId, $lot)
    {
        $queryDataString = "
        SELECT 
        *
        FROM mainentries 
        WHERE shedId =" . $shedId . " AND lot =" . $lot;

        $dataQuery = $this->db->query($queryDataString);
        $data_object = $dataQuery->getResult();

        return $data_object;
    }

    public function orderDescGetSingleDataMainEntry($shedId)
    {
        $query = "SELECT * from mainentries WHERE shedId = " .$shedId . " ORDER BY id DESC  LIMIT 1" ;
		// return $query;
        $dataQuery = $this->db->query($query);
		$data_object = $dataQuery->getResult();
		return $data_object;
    }
}
