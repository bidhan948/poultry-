<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransferDetail extends Model
{

    protected $table                = 'stocktransferdetails';
    protected $primaryKey           = 'id';
    protected $returnType           = 'object';
    protected $allowedFields        = [
        'toShed',
        'toLot',
        'male',
        'female',
        'description',
        'stockTransferId'
    ];

    public function getTransferTo($entrySource, $shedId, $lot)
    {
        $transferDetailQuery = "
        SELECT 
        std.toShed as shedId,
        std.toLot as lot,
        st.transferDate as date,
        SUM(std.male) as male,
        SUM(std.female) as female
        FROM stocktransferdetails std
        INNER JOIN stocktransfer st ON std.stockTransferId = st.id
        WHERE std.toShed = " . $shedId . " AND std.toLot =" . $lot . " 
        GROUP BY st.transferDate
        " . ($entrySource == 1 ? ' LIMIT 100 OFFSET 1' : '');
        $transferDetailArray = $this->db->query($transferDetailQuery);
        $data_object = $transferDetailArray->getResult();
        return $data_object;
    }
}
