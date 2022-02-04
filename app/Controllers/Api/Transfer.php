<?php

namespace App\Controllers\Api;

use App\Models\DailyEntry;
use App\Models\MainEntry;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\StockTransferDetail;
use App\Models\UserLog;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;

class Transfer extends ResourceController
{
    use ResponseTrait;

    // all transfers
    public function getAllTransfers()
    {
        $model = new \App\Models\StockTransfer();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $data = $model->getTransferWithTransferDetails($pageIndex, $pageSize);
        return $this->respond($data);
    }
    public function getTransfersByShedAndDate()
    {
        $model = new \App\Models\StockTransfer();
        $shedId = $this->request->getVar('fromShed');
        $date = $this->request->getVar('transferDate');
        $data = $model->getTransferDetailsByFromShedAndTransferDate($shedId, $date);
        return $this->respond($data);
    }

    public function updateTransfer()
    {
        // declaring a variable
        $id = $this->request->getVar('id');
        $fromShed = $this->request->getVar('fromShed');
        $fromLot = $this->request->getVar('fromLot');
        $date = $this->request->getVar('date');
        $dateBs = $this->request->getVar('dateBs');
        $transferDetails = $this->request->getVar('transferDetails');
        $transferMale = $transferDetails[0]->male;
        $transferFemale = $transferDetails[0]->female;
        $toShed = $transferDetails[0]->toShed;
        $toLot = $transferDetails[0]->toLot;

        // creating an instance for model
        $stockModel = new Stock();
        $stockTransferModel = new StockTransfer();
        $stockTransferModelDetail = new StockTransferDetail();
        // $mainEntryModel = new MainEntry();

        // logic part goes here
        // checking if transfer male and female is greater than stock 
        $stockDataFrom = $stockModel->where('shedId', $fromShed)->where('lot', $fromLot)->first();
        if ($stockDataFrom->male < $transferMale || $stockDataFrom->female < $transferFemale) {
            return $this->failForbidden('Transfer male or female quantity is insufficient');
        }

        // this is for storing old value of transfer and transfer details
        $stockDataTo = $stockModel->where('shedId', $toShed)->where('lot', $toLot)->first();
        $stockTransferDetailData = $stockTransferModelDetail->where('stockTransferId', $id)->first();
        $stockTransferData = $stockTransferModel->where('id', $id)->first();
        // $mainEntryDataFrom = $mainEntryModel->where('shedId', $fromShed)->where('lot', $fromLot)->first();
        // $mainEntryDataTo = $mainEntryModel->where('shedId', $toShed)->where('lot', $toLot)->first();

        $oldTransferMale = $stockTransferDetailData->male;
        $oldTransferFemale = $stockTransferDetailData->female;
        $newTransferMale = $transferMale - $oldTransferMale;
        $newTransferFemale = $transferFemale - $oldTransferFemale;
        $newTransferMaleFrom = $oldTransferMale - $transferMale;
        $newTransferFemaleFrom = $oldTransferFemale - $transferFemale;

        $stockTransfer = ['transferDate' => $date, 'transferDateBs' => $dateBs];
        $stockTransferDetail = ['male' => $transferMale, 'female' => $transferFemale];
        $stockTo = ['male' => $stockDataTo->male + $newTransferMale, 'female' => $stockDataTo->female + $newTransferFemale];
        $stockFrom = ['male' => $stockDataFrom->male + $newTransferMaleFrom, 'female' => $stockDataFrom->female + $newTransferFemaleFrom];
        // $mainEntryFrom = ['male' => $mainEntryDataFrom->male + $newTransferMale, 'female' => $mainEntryDataFrom->female + $newTransferFemale];
        // $mainEntryTo = ['male' => $mainEntryDataTo->male + $newTransferMale, 'female' => $mainEntryDataTo->female + $newTransferFemale];

        // this is for updating all the model
        $stockTransferModel->update($id, $stockTransfer);
        $stockTransferModelDetail->update($stockTransferDetailData->id, $stockTransferDetail);
        $stockModel->update($stockDataTo->id, $stockTo);
        $stockModel->update($stockDataFrom->id, $stockFrom);
        // $mainEntryModel->update($mainEntryDataFrom->id, $mainEntryFrom);
        // $mainEntryModel->update($mainEntryDataTo->id, $mainEntryTo);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Transfer updated Successfully'
        ];

        return $this->respond($response);
    }

    public function submitTransfer()
    {
        // declearing a variable 
        $fromShed = $this->request->getVar('fromShed');
        $fromLot = $this->request->getVar('fromLot');
        $transferAge = $this->request->getVar('transferAge');
        $breedTypeId = $this->request->getVar('breedTypeId');
        $ageInDays = $this->request->getVar('ageInDays');
        $date = $this->request->getVar('date');
        $dateBs = $this->request->getVar('dateBs');
        $transferDetails = $this->request->getVar('transferDetails');
        $toShed = $transferDetails[0]->toShed;
        $transferMale = $transferDetails[0]->male;
        $transferFemale = $transferDetails[0]->female;
        $description = $transferDetails[0]->description;

        // creating an instance for model
        $transferModel = new \App\Models\StockTransfer();
        $stockModel = new \App\Models\Stock();
        $mainEntryModel = new \App\Models\MainEntry();
        $transferDetailsModel = new \App\Models\StockTransferDetail();
        $dailyEntryModel = new DailyEntry();
        $dailyEntryLatest = $dailyEntryModel->getLatestDailyEntry($toShed);

        $stockData = $stockModel->where('shedId', $fromShed)->where('lot', $fromLot)->first();
        // $stockToData = $stockModel->where('shedId', $toShed)->first();

        $mainEntryLatest = $mainEntryModel->orderDescGetSingleDataMainEntry($toShed);

        $stockToData = $stockModel->orderDescGetSingleData($toShed);

        $mainEntryData = $mainEntryModel->where('shedId', $toShed)->first();

        if (empty($mainEntryLatest[0])) {
            $toLot = $dailyEntryLatest[0]->lot == NULL ? $fromLot : $dailyEntryLatest[0]->lot;
        } else {
            $toLot = $mainEntryLatest[0]->lot;
        }

        // cheking if it is empty
        if (empty($fromShed)) {
            return $this->failForbidden("Please select from shed");
        }
        if ($fromShed == $toShed) {
            return $this->failForbidden("Transfer cannot be done to the same shed");
        }
        if (empty($date)) {
            return $this->failForbidden("Please select from date");
        }
        if (empty($transferDetails)) {
            return $this->failForbidden("Please fill transfer details");
        }
        $tempDate = Time::parse($date);
        if ($tempDate->isAfter(date('Y-m-d'))) {
            return $this->failForbidden("Selected date is invalid");
        }

        // Logic part goes here

        if (empty($stockData)) {
            return $this->failForbidden('Cannot transfer from this shed. Stock Not Found');
        }

        // checking whether the transfer male and female is  greater than stock
        if ($transferMale > $stockData->male || $transferFemale > $stockData->female) {
            return $this->failForbidden('Transfer male or female quantity is insufficient');
        }


        // Updating from transfer
        $stockFromTransferMale = $stockData->male - $transferMale;
        $stockFromTransferFemale = $stockData->female - $transferFemale;
        $fromStock = ['male' => $stockFromTransferMale, 'female' => $stockFromTransferFemale];
        $stockModel->update($stockData->id, $fromStock);

        // if main entry is empty then creating one 
        if (empty($stockToData[0])) {
            $stockModel->insert(
                [
                    'shedId' => $toShed,
                    'lot' => $toLot,
                    'male' => $transferMale,
                    'female' => $transferFemale,
                    'entryMale' => $transferMale,
                    'entryFemale' => $transferFemale,
                    'breedTypeId' => $breedTypeId,
                    'entryDate' => $date,
                    'entrySource' => 1,
                    'entryDateBs' => $dateBs,
                    'initialAge' => $ageInDays,
                ]
            );
            $id =  $transferModel->insert(
                [
                    'fromShed' => $fromShed,
                    'fromLot' => $fromLot,
                    'transferAge' => $transferAge,
                    'transferDate' => $date,
                    'transferDateBs' => $dateBs
                ]
            );

            $transferDetailsModel->insert(
                [
                    'toShed' => $toShed,
                    'toLot' => $toLot,
                    'male' => $transferMale,
                    'female' => $transferFemale,
                    'description' => $description,
                    'stockTransferId' => $id,
                ]
            );
        } else {
            // this is for creating transfer and transferDetails
            if ($stockToData[0]->male == 0 && $stockToData[0]->female == 0) {
                $stockModel->insert(
                    [
                        'shedId' => $toShed,
                        'lot' => $fromLot,
                        'male' => $transferMale,
                        'female' => $transferFemale,
                        'entryMale' => $transferMale,
                        'entryFemale' => $transferFemale,
                        'breedTypeId' => $breedTypeId,
                        'entryDate' => $date,
                        'entrySource' => 1,
                        'entryDateBs' => $dateBs,
                        'initialAge' => $ageInDays,
                    ]
                );
                $id =  $transferModel->insert(
                    [
                        'fromShed' => $fromShed,
                        'fromLot' => $fromLot,
                        'transferAge' => $transferAge,
                        'transferDate' => $date,
                        'transferDateBs' => $dateBs
                    ]
                );

                $transferDetailsModel->insert(
                    [
                        'toShed' => $toShed,
                        'toLot' => $fromLot,
                        'male' => $transferMale,
                        'female' => $transferFemale,
                        'description' => $description,
                        'stockTransferId' => $id,
                    ]
                );
            } else {
                $id =  $transferModel->insert(
                    [
                        'fromShed' => $fromShed,
                        'fromLot' => $fromLot,
                        'transferAge' => $transferAge,
                        'transferDate' => $date,
                        'transferDateBs' => $dateBs
                    ]
                );

                $transferDetailsModel->insert(
                    [
                        'toShed' => $toShed,
                        'toLot' => $toLot,
                        'male' => $transferMale,
                        'female' => $transferFemale,
                        'description' => $description,
                        'stockTransferId' => $id,
                    ]
                );
            }
            // updating To Transfer 
            $stockToTransferMale = $stockToData[0]->male + $transferMale;
            $stockToTransferFemale = $stockToData[0]->female + $transferFemale;
            if (!empty($stockToData[0])) {
                if ($stockToData[0]->male != 0 || $stockToData[0]->female != 0) {
                    $toStock = ['male' => $stockToTransferMale, 'female' => $stockToTransferFemale];
                    $stockModel->update($stockToData[0]->id, $toStock);
                }
            }
        }

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Transfer Added Successfully'
        ];

        return $this->respond($response);
    }

    public function addUpdateTransfer()
    {
        $transferModel = new \App\Models\StockTransfer();
        $stockModel = new \App\Models\Stock();
        $mainEntryModel = new \App\Models\MainEntry();
        $transferDetailsModel = new \App\Models\StockTransferDetail();
        $userLog = new UserLog();

        $fromShed = $this->request->getVar('fromShed');
        if (empty($fromShed)) {
            return $this->failForbidden("Please select from shed");
        }
        $fromLot = $this->request->getVar('fromLot');


        $date = $this->request->getVar('date');
        if (empty($date)) {
            return $this->failForbidden("Please select from date");
        }
        $dateBs = $this->request->getVar('dateBs');

        $transferDetails = $this->request->getVar('transferDetails');
        if (empty($transferDetails)) {
            return $this->failForbidden("Please fill transfer details");
        }
        $transferDataToCheck = $transferModel->where('fromShed', $fromShed)->where('transferDate', $date)->first();
        // if(!empty($transferDataToCheck)) {
        //     return $this->response->setStatusCode( "Cannot transfer ");
        // }
        $transferAge = $this->request->getVar('transferAge');
        $totalMaleToBeTransfer = 0;
        $totalFemaleToBeTransfer = 0;

        //validation
        $uqShed = array();
        foreach ($transferDetails as $item) {

            if (empty($item->toShed)) {
                return $this->failForbidden("Please select shed to be transfered");
            }
            if ($item->toShed == $fromShed) {
                return $this->failForbidden("Cannot transfer to same shed");
            }

            if (empty($item->male)) {
                $male = 0;
            } else {
                $male = $item->male;
            }
            if (empty($item->female)) {
                $female = 0;
            } else {
                $female = $item->female;
            }
            $totalMaleToBeTransfer += $male;
            $totalFemaleToBeTransfer += $female;

            if (in_array($item->toShed, $uqShed)) {
                return $this->failForbidden("Cannot transfer to same shed");
            }
            array_push($uqShed, $item->toShed);
        }

        $stockData = $stockModel->where('shedId', $fromShed)->where('lot', $fromLot)->first();

        if (empty($stockData)) {
            return $this->failForbidden("Cannot transfer from this shed. Stock Not Found");
        }


        $transferDataToBeInserted = [
            'fromShed' => $fromShed,
            'fromLot' => $fromLot,
            'transferAge' => $transferAge,
            'transferDate' => $date,
            'transferDateBs' => $dateBs,
        ];

        // $response = [
        //     'status'   => 200,
        //     'error'    => null,
        //     'messages' => 'Transfer Added Successfully'
        // ];

        // return $this->respond($response);
        if (empty($transferDataToCheck)) {
            if ($totalMaleToBeTransfer > $stockData->male || $totalFemaleToBeTransfer > $stockData->female) {
                return $this->failForbidden("Transfer number of male or female exceed stock");
                //return $this->response->setStatusCode( "Transfer number of male or female exceed stock");
            }
            // return 'Error';
            $id = $transferModel->insert($transferDataToBeInserted);

            $mainEntryData = $mainEntryModel->getRecentlyAddedMainEntryByShedIdAndLot($fromShed, $fromLot);
            if (!empty($mainEntryData)) {
                $mainEntryStatus = [
                    'status' => 1
                ];
                foreach ($mainEntryData as $item) {
                    $mainEntryModel->update($item->id, $mainEntryStatus);
                }
            }

            if ($totalMaleToBeTransfer == $stockData->male && $totalFemaleToBeTransfer == $stockData->female) {
                $mainEntryData = $mainEntryModel->getRecentlyAddedOrActiveMainEntryByShedIdAndLot($fromShed, $fromLot);
                if (!empty($mainEntryData)) {
                    $mainEntryStatus = [
                        'status' => 3
                    ];
                    foreach ($mainEntryData as $item) {
                        $mainEntryModel->update($item->id, $mainEntryStatus);
                    }
                }
            }

            $userLogData['stockMale'] = $stockData->male;
            $userLogData['stockFemale'] = $stockData->female;
            $stockMale = $stockData->male;
            $stockFemale = $stockData->female;
            $stock = [
                'male' => $stockData->male - $totalMaleToBeTransfer,
                'female' => $stockData->female - $totalFemaleToBeTransfer
            ];

            $stockModel->update($stockData->id, $stock);
            //$transferDetailsModel->delete(['stockTransferId' => $id]);
            foreach ($transferDetails as $item) {
                empty($item->toLot) ? $toLot = $fromLot : $toLot = $item->toLot;
                $toShed = $item->toShed;
                $stockDataForDetails = $stockModel->where('shedId', $toShed)->where('lot', $toLot)->first();

                $toLotData = [
                    'toShed' => $toShed,
                    'toLot' => $toLot,
                    'male' => $item->male,
                    'female' => $item->female,
                    'description' => $item->description,
                    'stockTransferId' => $id
                ];

                // this is for user log
                $userLogData = [
                    'date' => date('Y/m/d h:i:s a', time()),
                    'transferFrom' => $fromShed,
                    'stockMale' =>  $stockMale,
                    'stockFemale' => $stockFemale,
                    'transferTo' => $toShed,
                    'transferFrom' => $fromShed,
                    'fromLot' => $fromLot,
                    'male' => $totalMaleToBeTransfer,
                    'female' => $totalFemaleToBeTransfer,
                    'toLot' => $toLot,
                    'action' => 'transfer',
                    'user' => session()->get('username')
                ];
                $userLog->insert($userLogData);


                $transferDetailsModel->insert($toLotData);

                if (!empty($stockDataForDetails)) {
                    $stock = [
                        'male' => $stockDataForDetails->male + $item->male,
                        'female' => $stockDataForDetails->female + $item->female
                    ];
                    $stockModel->update($stockDataForDetails->id, $stock);
                } else {
                    $stock = [
                        'shedId'  => $toShed,
                        'lot' => $toLot,
                        'male'  =>  $item->male,
                        'entryDate' => $this->request->getVar('date'),
                        'entryDateBs' => $this->request->getVar('dateBs'),
                        'initialAge' => $this->request->getVar('transferAge'),
                        'entryMale' => $item->male,
                        'entryFemale' => $item->female,
                        'entrySource' => 1,
                        'breedTypeId' => $this->request->getVar('breedTypeId'),
                        'female'  => $item->female,
                    ];
                    $stockModel->insert($stock);
                }
            }
        } else {
            $prevTransferDetailsMale = $transferDetailsModel->selectSum('male')->where('stockTransferId', $transferDataToCheck->id)->first();
            $prevTransferDetailsFemale = $transferDetailsModel->selectSum('female')->where('stockTransferId', $transferDataToCheck->id)->first();

            $prevTransferDetails = $transferDetailsModel->where('stockTransferId', $transferDataToCheck->id)->get()->getResult();

            if ($totalMaleToBeTransfer > ($stockData->male + $prevTransferDetailsMale->male) || $totalFemaleToBeTransfer > ($stockData->female + $prevTransferDetailsFemale->female)) {
                return $this->failForbidden("Transfer number of male or female exceed stock");
            }
            if ($totalMaleToBeTransfer != ($stockData->male + $prevTransferDetailsMale->male) && $totalFemaleToBeTransfer != ($stockData->female + $prevTransferDetailsFemale->female)) {
                $mainEntryData = $mainEntryModel->getAllMainEntryByShedIdAndLot($fromShed, $fromLot);
                if (!empty($mainEntryData)) {
                    $mainEntryStatus = [
                        'status' => 1
                    ];
                    foreach ($mainEntryData as $item) {
                        $mainEntryModel->update($item->id, $mainEntryStatus);
                    }
                }
            }
            if ($totalMaleToBeTransfer == ($stockData->male + $prevTransferDetailsMale->male) && $totalFemaleToBeTransfer == ($stockData->female + $prevTransferDetailsFemale->female)) {
                $mainEntryData = $mainEntryModel->getAllMainEntryByShedIdAndLot($fromShed, $fromLot);
                if (!empty($mainEntryData)) {
                    $mainEntryStatus = [
                        'status' => 3
                    ];
                    foreach ($mainEntryData as $item) {
                        $mainEntryModel->update($item->id, $mainEntryStatus);
                    }
                }
            }
            $stock = [
                'male' => $stockData->male + $prevTransferDetailsMale->male - $totalMaleToBeTransfer,
                'female' => $stockData->female + $prevTransferDetailsMale->female - $totalFemaleToBeTransfer
            ];
            $stockModel->update($stockData->id, $stock);

            foreach ($prevTransferDetails as $i => $item) {
                $prevItemStock = $stockModel->where('shedId', $item->toShed)->where('lot', $item->toLot)->first();
                $stock = [
                    'male' => $stockData->male - $prevItemStock->male,
                    'female' => $stockData->female - $prevItemStock->female
                ];
                $stockModel->update($prevItemStock->id, $stock);
            }

            $transferDetailsModel->where('stockTransferId', $transferDataToCheck->id)->delete();

            foreach ($transferDetails as $item) {
                empty($item->toLot) ? $toLot = $fromLot : $toLot = $item->toLot;
                $toShed = $item->toShed;
                $stockDataForDetails = $stockModel->where('shedId', $toShed)->where('lot', $toLot)->first();

                $toLotData = [
                    'toShed' => $toShed,
                    'toLot' => $toLot,
                    'male' => $item->male,
                    'female' => $item->female,
                    'description' => $item->description,
                    'stockTransferId' => $transferDataToCheck->id
                ];
                $transferDetailsModel->insert($toLotData);

                if (!empty($stockDataForDetails)) {
                    $stock = [
                        'male' => $stockDataForDetails->male + $item->male,
                        'female' => $stockDataForDetails->female + $item->female
                    ];
                    $stockModel->update($stockDataForDetails->id, $stock);
                } else {
                    $stock = [
                        'shedId'  => $toShed,
                        'lot' => $toLot,
                        'male'  =>  $item->male,
                        'entryDate' => $this->request->getVar('date'),
                        'initialAge' => $this->request->getVar('transferAge'),
                        'entryMale' => $item->male,
                        'entryFemale' => $item->female,
                        'entrySource' => 1,
                        'breedTypeId' => $this->request->getVar('breedTypeId'),
                        'female'  => $item->female,
                    ];
                    $stockModel->insert($stock);
                }
            }
        }
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Transfer Added Successfully'
        ];

        return $this->respond($response);
    }
}
