<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Report extends ResourceController
{
    use ResponseTrait;

    // all entries
    public function getFarmReport()
    {
        $reportModel = new \App\Models\FarmReport();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $fromDate = $this->request->getVar('fromDate');
        $toDate = $this->request->getVar('toDate');
        $group = $this->request->getVar('group');
        $shed = $this->request->getVar('shedId');
        $lot = $this->request->getVar('lot');


        if (!empty($fromDate)) {
            if (empty($toDate)) {
                return $this->failForbidden('Please select to date');
            }
        } else {
            if (!empty($toDate)) {
                return $this->failForbidden('Please select from date');
            }
        }

        if (!empty($group) && empty($shed)) {
            $shedModel = new \App\Models\Shed();
            $sheds =  $shedModel->where('groupId', $group)->get()->getResult();
            if (empty($sheds)) {
                return $this->failForbidden('Selected group does not have any sheds');
            }
        }

        $data = $reportModel->getFarmReport($pageIndex, $pageSize, $fromDate, $toDate, $group, $shed, $lot);
        return $this->respond($data);
    }

    public function getData()
    {
        $reportModel = new \App\Models\FarmReport();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $fromDate = $this->request->getVar('fromDate');
        $toDate = $this->request->getVar('toDate');
        $group = $this->request->getVar('group');
        $shed = $this->request->getVar('shedId');
        $lot = $this->request->getVar('lot');


        if (!empty($fromDate)) {
            if (empty($toDate)) {
                return $this->failForbidden('Please select to date');
            }
        } else {
            if (!empty($toDate)) {
                return $this->failForbidden('Please select from date');
            }
        }

        if (!empty($group) && empty($shed)) {
            $shedModel = new \App\Models\Shed();
            $sheds =  $shedModel->where('groupId', $group)->get()->getResult();
            if (empty($sheds)) {
                return $this->failForbidden('Selected group does not have any sheds');
            }
        }

        $data = $reportModel->getFarmReport($pageIndex, $pageSize, $fromDate, $toDate, $group, $shed, $lot);
        return $this->respond($data);
    }
}
