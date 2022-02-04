<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Stock;

class Appstock extends ResourceController
{
    use ResponseTrait;

    // all entries
    public function getAllStocks()
    {
        $model = new Stock();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $data = $model->getStockForList($pageIndex, $pageSize);
        return $this->respond($data);
    }

    public function getByShedId()
    {
        $model = new Stock();
        $shedId = $this->request->getVar('shedId');
        $date = $this->request->getVar('transferDate');
        $data = $model->getByShedIdWithDetails($shedId,'',$date);
        return $this->respond($data);
    }
   
}
