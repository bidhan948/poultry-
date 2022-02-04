<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransfer extends Model
{

    protected $table                = 'stocktransfer';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'fromShed',
        'fromLot',
        'transferAge',
        'transferDate',
        'transferDateBs'
    ];
    public function getTransferDetailsByFromShedAndTransferDate($shedId,$date)
    {
        $queryString ="
        SELECT 
             std.toShed as toShed,
             std.toLot as toLot,
             std.male as male,
             std.female as female,
             std.description as description,
             st.transferAge as ageInDays,
             CONCAT(FLOOR(st.transferAge/7),'.',(st.transferAge%7)) as ageInWeeks,
             s.name as toShedName
        FROM stocktransferdetails std
        LEFT JOIN sheds s on std.toShed = s.id
        INNER JOIN stocktransfer st on std.stockTransferId = st.id
        WHERE st.fromShed=".$shedId." AND st.transferDate ='".$date."'
        ";
        $dataQuery = $this->db->query($queryString);
        $data_object = $dataQuery->getResult();
        return $data_object;
    }


    public function getTransferWithTransferDetails($pageIndex, $pageSize)
    {
        $queryDataString = "
        SELECT 
        st.id as id,
        st.fromShed as fromShedId,
        st.fromLot as fromLot,
        st.transferDate as transferDate,
        st.transferDateBs as transferDateBs,
        st.transferAge as transferAge,
        CONCAT(FLOOR(st.transferAge/7),'.',(st.transferAge%7)) as transferAgeWeeks,
        s.name as fromShedName
        FROM stocktransfer st
        LEFT JOIN sheds s on st.fromShed = s.id 
        ORDER BY st.transferDate DESC" ;

        $queryCountString = "
        SELECT 
        Count(*) as count
        FROM stocktransfer
        ";

        $dataQuery = $this->db->query($queryDataString);
        $countQuery = $this->db->query($queryCountString);

        // your object result
        $data_object = $dataQuery->getResult();
        $count_object = (int)$countQuery->getResult();
        foreach ($data_object as $i => $item) {
            $dataSubQuery = $this->db->query(
                "
             SELECT 
             std.toShed as toShed,
             std.toLot as toLot,
             std.male as male,
             std.female as female,
             std.description as description,
             s.name as toShedName
             FROM stocktransferdetails std
             LEFT JOIN sheds s on std.toShed = s.id 
             WHERE std.stockTransferId =" . $item->id
            );
            $transferDetails = $dataSubQuery->getResult();
            if (!empty($transferDetails)) {
                $data_object[$i]->transferDetails = $transferDetails;
            }
        }



        $result = [
            'data'   => $data_object,
            'count'    => $count_object,
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize
        ];

        return $result;
    }


    public function getTransferFrom($shedId, $lot)
    {
        
        $transferDataQuery = "
        SELECT 
            st.fromShed as shedId,
            st.fromLot as lot,
            st.transferDate as date,
            SUM(std.male) as male,
            SUM(std.female) as female
        FROM stocktransfer st
        INNER JOIN stocktransferdetails std ON std.stockTransferId = st.id
        WHERE st.fromShed = " . $shedId . " AND st.fromLot =" . $lot . " 
        GROUP BY st.transferDate
        ";
        $transferDataArray = $this->db->query($transferDataQuery);
        $data_object = $transferDataArray->getResult();
        return $data_object;
    }

  
}
