<?php

namespace App\Models;

use CodeIgniter\Model;

class Stock extends Model
{

    protected $table                = 'stock';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'shedId',
        'male',
        'female',
        'lot',
        'entryDate',
        'entryDateBs',
        'initialAge',
        'entryMale',
        'entryFemale',
        'breedTypeId',
        'entrySource'
    ];

    public function getStockForList($pageIndex, $pageSize)
    {

        $queryDataString = "
        SELECT 
        me.id as id,
        me.shedId as shedId,
        me.male as male,
        me.female as female,
        g.name as groupName,
        s.name as shedName,
        s.description as shedDetails,
        me.lot as lot, 
        me.breedTypeId as breedTypeId,
        b.name as breedTypeName
        FROM stock me
        LEFT JOIN sheds s on me.shedId = s.id 
        LEFT JOIN groups g on s.groupId = g.id 
        LEFT JOIN breeds b on me.breedTypeId = b.id 
        WHERE me.male > 0 OR me.female > 0
        ORDER BY me.id ASC
        LIMIT " . $pageSize . " OFFSET " . ($pageIndex - 1) * $pageSize;

        $queryCountString = "
        SELECT 
        Count(*) as count
        FROM stock
        ";

        $dataQuery = $this->db->query($queryDataString);
        $countQuery = $this->db->query($queryCountString);
        // your object result
        $data_object = $dataQuery->getResult();
        $count_object = (int)$countQuery->getResult();

        $result = [
            'data'   => $data_object,
            'count'    => $count_object,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize
        ];

        return $result;
    }


    public function getByShedId($shedId)
    {
        $queryDataString = "
        SELECT 
        *
        FROM stock s
        WHERE (s.male > 0 OR s.female > 0) AND s.shedId =" . $shedId;

        $dataQuery = $this->db->query($queryDataString);
        // your object result
        $data_object = $dataQuery->getResult();

        return $data_object;
    }

    public function getByShedIdWithDetails($shedId, $lot = null, $date = null)
    {
        $filterQuery = '';
        $selectQuery = '';
        if (!empty($lot)) {
            $filterQuery = " AND s.lot =" . $lot;
        }
        if(!empty($date)) {
            $selectQuery = " DATEDIFF('".$date."',s.entryDate) + s.initialAge as ageInDays,";
            $selectQuery .= " CONCAT(FLOOR((DATEDIFF('".$date."',s.entryDate)+ s.initialAge)/7),'.',(DATEDIFF('".$date."',s.entryDate)+ s.initialAge)%7) as ageInWeeks,";
        } else {
            $selectQuery = " s.initialAge as ageInDays,";
            $selectQuery .= " s.initialAge as ageInWeeks,";
        }
        $queryDataString = "
        SELECT 
        ".$selectQuery."
        s.id as id,
        s.shedId as shedId,
        s.male as male,
        s.female as female,
        s.lot as lot,
        s.breedTypeId as breedTypeId,
        sh.name as shedName,
        sh.description as shedDetail
        FROM stock s
        INNER JOIN sheds sh ON s.shedId = sh.id
        WHERE (s.male > 0 OR s.female > 0) AND s.shedId =" . $shedId . $filterQuery . " LIMIT 1";

        $dataQuery = $this->db->query($queryDataString);
        //(object)[];
        // your object result
        $data_object = $dataQuery->getResult();


        return $data_object;
    }

    public function orderDescGetSingleData($shedId)
    {
        $query = "SELECT * from stock WHERE shedId = " .$shedId . " ORDER BY id DESC  LIMIT 1" ;
		$dataQuery = $this->db->query($query);
		$data_object = $dataQuery->getResult();
		return $data_object;
    }
   
}
