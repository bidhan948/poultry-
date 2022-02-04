<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use App\Models\MainEntry;
use App\Models\Stock;
use App\Models\DailyEntry;
use App\Models\DailyMedicineVaccine;
use App\Models\DailyRemark;
use App\Models\ExtendedMainEntry;
use App\Models\UserLog;

class Entry extends ResourceController
{
    use ResponseTrait;

    // all entries
    public function getAllMainEntries()
    {
        $model = new MainEntry();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $data = $model->getMainEntriesForList($pageIndex, $pageSize);

        return $this->respond($data);
    }
    public function getShedDataFromEntries()
    {
        $model = new MainEntry();
        $shedId = $this->request->getVar('shed');
        $data = $model->getShedDataFromEntriesByShed($shedId);
        return $this->respond($data);
    }
    public function getDailyShedData()
    {
        $dailyEntryModel = new DailyEntry();
        $medVacDailyModel = new DailyMedicineVaccine();
        $shedId = $this->request->getVar('shed');
        $date = $this->request->getVar('date');
        $whereClauseArrayForDailyEntry = array('shedId' =>  $shedId, 'date' => $date);
        $uniqueDailyEntry = $dailyEntryModel->where($whereClauseArrayForDailyEntry)->first();
        if (!empty($uniqueDailyEntry)) {
            $medicineVaccineData = $medVacDailyModel->where('dailyEntryId', $uniqueDailyEntry->id)->get()->getresult();
            $uniqueDailyEntry->medicineVaccine = $medicineVaccineData;
            return $this->respond($uniqueDailyEntry);
        } else {
            $x = json_encode(json_decode(""));
            return $this->respond($x);
        }
    }
    public function getAllDailyData()
    {
        $dailyEntryModel = new DailyEntry();
        $pageSize = $this->request->getVar('pageSize');
        $pageIndex = $this->request->getVar('pageIndex');
        $shedId = $this->request->getVar('shedId');
        $date = $this->request->getVar('date');
        $lot = $this->request->getVar('lot');
        $data = $dailyEntryModel->getDailyEntriesForList($pageIndex, $pageSize, $shedId, $date, $lot);
        return $this->respond($data);
    }
    public function getArrivalAge()
    {
        $shedId = $this->request->getVar('shedId');
        $lot = $this->request->getVar('lot');
        $arrivalDate = $this->request->getVar('arrivalDate');
        $model = new MainEntry();
        $singleMainEntry = $model->where(['shedId' => $shedId, 'lot' => $lot])->first();
        $tempDateAd =  Time::parse($arrivalDate);
        $dateAd = $tempDateAd->toDateString();
        if ($tempDateAd->isBefore($singleMainEntry->arrivalDate)) {
            return $this->failForbidden("Date invalid");
        } else {
            $date_diff = date_diff(date_create($singleMainEntry->arrivalDate), date_create($dateAd));

            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Arrival age',
                'arrivalAge' => $singleMainEntry->arrivalAge + $date_diff->days
            ];
        }
        return $this->respond($response);
    }
    // add/update entries
    public function addUpdateMainEntries()
    {
        $model = new MainEntry();
        $extendedModel = new ExtendedMainEntry();
        $stockModel = new Stock();
        $userLogModel = new UserLog();
        // just declaring variables for response
        $shedId = $this->request->getVar('shedId');
        $lot = $this->request->getVar('lot');
        $maleQuantity = $this->request->getVar('arrivalQuantityMale');
        $femaleQuantity = $this->request->getVar('arrivalQuantityFemale');
        $breedTypeId = $this->request->getVar('breedTypeId');
        $arrivalDateBs = $this->request->getVar('arrivalDateBs');
        $arrivalDate = $this->request->getVar('arrivalDate');
        $arrivalAge = $this->request->getVar('arrivalAge');
        //server side validation of entry
        $shedDataOfMainEntry = $model->getShedDataFromEntriesByShed($shedId);
        if (!empty($shedDataOfMainEntry)) {
            $singleShedDataOfMainEntry = $shedDataOfMainEntry[0];
            if ($singleShedDataOfMainEntry->lot != $lot || $singleShedDataOfMainEntry->breedTypeId != $breedTypeId) {
                return $this->failForbidden("Lot and Breed type of selected shed can't be changed");
            }
        }
        $data = [
            'lot' => $lot,
            'shedId'  => $shedId,
            'arrivalDate'  => $arrivalDate,
            'arrivalDateBs'  => $arrivalDateBs,
            'arrivalAge'  => $arrivalAge,
            'arrivalQuantityMale'  => $maleQuantity,
            'arrivalQuantityFemale'  => $femaleQuantity,
            'breedTypeId'  => $breedTypeId,
            'gender'  => $this->request->getVar('gender'),
            'status'  => $this->request->getVar('status'),
            'description'  => $this->request->getVar('description'),
        ];
        $id = $this->request->getVar('id');
        if (empty($id)) {
            $oldEntry = $model->where('shedId', $shedId)->where('lot', $lot)->first();

            // this is for calculating arrival age 

            if (!empty($oldEntry)) {

                if ($arrivalDateBs < $oldEntry->arrivalDateBs) {
                    return $this->failForbidden("Date invalid");
                }

                $tempDateAd =  Time::parse($arrivalDate);
                $dateAd = $tempDateAd->toDateString();

                $day_count = date_diff(date_create($oldEntry->arrivalDate), date_create($dateAd));
                $arrival_age = $oldEntry->arrivalAge + $day_count->d;

                if ($oldEntry->status != '1' && $oldEntry->status != '0') {
                    return  $this->failForbidden('Lot ' . $lot . ' for selected shed already exist');
                }
                $id = $oldEntry->id;
                $extendedEntryData = [
                    'arrivalDate'  => $this->request->getVar('arrivalDate'),
                    'arrivalDateBs'  => $this->request->getVar('arrivalDateBs'),
                    'arrivalAge'  => $arrival_age,
                    'arrivalQuantityMale'  => $maleQuantity,
                    'mainEntryId' => $id,
                    'arrivalQuantityFemale'  => $femaleQuantity,
                    'breedTypeId'  => $breedTypeId
                ];
                $extendedModel->insert($extendedEntryData);

                // this is for user log
                $check = "DE";
            } else {
                // this is for user log
                $check = "ME";
                $model->insert($data);
            }
            //check for unique shedId + lot in stock
            $whereClauseArrayForStock = array('shedId' => $shedId, 'lot' => $lot);
            $uniqueStock = $stockModel->where($whereClauseArrayForStock)->first();
            if (empty($uniqueStock)) {
                $stock = [
                    'shedId'  => $shedId,
                    'lot' => $lot,
                    'male'  => $maleQuantity,
                    'entryDate' => $this->request->getVar('arrivalDate'),
                    'entryDateBs' => $this->request->getVar('arrivalDateBs'),
                    'entryMale' => $maleQuantity,
                    'entryFemale' => $femaleQuantity,
                    'initialAge' => $this->request->getVar('arrivalAge'),
                    'breedTypeId'  => $breedTypeId,
                    'female'  => $femaleQuantity,
                ];
                $stockModel->insert($stock);
            } else {
                $stock = [
                    'male'  => $maleQuantity +  $uniqueStock->male,
                    'female'  => $femaleQuantity + $uniqueStock->female,
                ];
                $stockModel->update($uniqueStock->id, $stock);
            }

            // this is for user logs
            // if ($check == "DE") {
            //     $userLogData = [
            //         'date' => date('Y/m/d h:i:s a', time()),
            //         'entryMale' => $maleQuantity,
            //         'user' => session()->get('username'),
            //         'entryFemale' => $femaleQuantity,
            //         'entryShedId' => $shedId,
            //         'action' => 'Double Entry',
            //         'stockMale' => $uniqueStock->male,
            //         'stockFemale' => $uniqueStock->female,
            //     ];
            //     $userLogModel->insert($userLogData);
            // } else {
            //     $userLogData = [
            //         'date' => date('Y/m/d h:i:s a', time()),
            //         'entryMale' => $maleQuantity,
            //         'user' => session()->get('username'),
            //         'entryFemale' => $femaleQuantity,
            //         'action' => 'Main Entry',
            //         'entryShedId' => $shedId,
            //         'stockMale' => $uniqueStock->male,
            //         'stockFemale' => $uniqueStock->female
            //     ];
            //     $userLogModel->insert($userLogData);
            // }

            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => 'Main Entry Created Successfully'
            ];
        } else {
            // $whereClauseArrayForMainEntry = array('shedId' =>  $shedId, 'lot' => $lot);
            $oldEntry = $model->where('id', $id)->first();
            if ($oldEntry->status > 1) {
                return $this->failForbidden('Nope');
            }

            $id = $oldEntry->id;
            $extendedEntries = $this->request->getVar('extendedMainEntry');
            $totalExtenedMaleQuantity = 0;
            $totalExtenedFemaleQuantity = 0;
            $totalExtenedMaleQuantityLatest = 0;
            $totalExtenedFemaleQuantityLatest = 0;
            $extendedEntryLatestArray = array();
            $singleOldStock = $stockModel->where('shedId', $oldEntry->shedId)->where('lot', $oldEntry->lot)->first();
            if (!empty($extendedEntries)) {

                // checking if double entry date with main entry
                $tempDate = Time::parse($oldEntry->arrivalDate);
                foreach ($extendedEntries as $key => $extendEntry) {
                    if ($tempDate->isAfter($extendEntry->arrivalDate)) {
                        return $this->failForbidden("Date invalid");
                    }
                }

                // subtracting arrival male and female before deleting
                $extendedEntryBefore = $extendedModel->where('mainEntryId', $id)->get()->getResult();

                foreach ($extendedEntryBefore as $key => $extenedEntry) {
                    $totalExtenedMaleQuantity += $extenedEntry->arrivalQuantityMale;
                    $totalExtenedFemaleQuantity += $extenedEntry->arrivalQuantityFemale;
                } 

                $extendedModel->where(['mainEntryId' => $id])->delete();

               
                //on update inserting individual data on extended
                foreach ($extendedEntries as $key => $extendedEntry) {

                    // $day_count = date_diff(date_create($oldEntry->arrivalDate), date_create($extendEntry->arrivalDate));
                    $totalExtenedMaleQuantityLatest += $extendedEntry->arrivalQuantityMale;
                    $totalExtenedFemaleQuantityLatest += $extendedEntry->arrivalQuantityFemale;
                    $data =
                        [
                            'arrivalQuantityMale' => $extendedEntry->arrivalQuantityMale,
                            'arrivalQuantityFemale' => $extendedEntry->arrivalQuantityFemale,
                            'arrivalDateBs' => $extendedEntry->arrivalDateBs,
                            'arrivalDate' => $extendedEntry->arrivalDate,
                            // 'arrivalAge' => $oldEntry->arrivalAge + $day_count->d,
                            'mainEntryId' => $oldEntry->id,
                            'breedTypeId' => $oldEntry->breedTypeId
                        ];
                    array_push($extendedEntryLatestArray, $data);

                    // this is for user log inserting double entry
                    // $userExtendedData = [
                    //     'date' => date('Y/m/d h:i:s a', time()),
                    //     'user' => session()->get('username'),
                    //     'entryMale' =>  $extendedEntry->arrivalQuantityMale,
                    //     'entryMale' =>  $extendedEntry->arrivalQuantityFemale,
                    //     'entryShedId' => $shedId,
                    //     'stockMale' => $stockMale,
                    //     'stockFemale' => $stockFemale,
                    //     'action' => 'Double Entry',
                    // ];

                    // $userLogModel->insert($userExtendedData);
                }

                // $singleStock = $stockModel->where('shedId', $shedId)->where('lot', $lot)->first();
                // $arr = [
                //     'male' => $singleStock->male + $totalExtenedMaleQuantity,
                //     'female' => $singleStock->female + $totalExtenedFemaleQuantity,
                // ];
                // $stockModel->update($singleStock->id, $arr);
            }
        
            //for new entry
               // for stock log 
               $stockMale = $singleOldStock->male;
               $stockFemale = $singleOldStock->female;
               $arr = [
                   'male' => $stockMale - $totalExtenedMaleQuantity -$oldEntry->arrivalQuantityMale,
                   'female' => $stockFemale - $totalExtenedFemaleQuantity- $oldEntry->arrivalQuantityFemale,
               ];
               if ($arr['male'] < 0 || $arr['female'] < 0) {
                return  $this->failForbidden('Inavalid quantity male and female');
               }
               if (!empty($extendedEntryLatestArray)) {
                   $extendedModel->insertBatch($extendedEntryLatestArray);
               }
               $stockModel->update($singleOldStock->id, $arr);
            //    $arr = [
            //        'male' => $stockMale - $totalExtenedMaleQuantity,
            //        'female' => $stockFemale - $totalExtenedFemaleQuantity,
            //    ];

            //    $stockModel->update($singleOldStock->id, $arr);
            //check for unique shedId + lot in stock
            $whereClauseArrayForStock = array('shedId' => $shedId, 'lot' => $lot);
            $uniqueStock = $stockModel->where($whereClauseArrayForStock)->first();
            if (empty($uniqueStock)) {
                $stock = [
                    'shedId'  => $shedId,
                    'lot' => $lot,
                    'male'  => $maleQuantity + $totalExtenedMaleQuantityLatest,
                    'entryDate' => $this->request->getVar('arrivalDate'),
                    'entryDateBs' => $this->request->getVar('arrivalDateBs'),
                    'initialAge' => $this->request->getVar('arrivalAge'),
                    'entryMale' => $maleQuantity + $totalExtenedMaleQuantityLatest,
                    'entryFemale' => $femaleQuantity + $totalExtenedFemaleQuantityLatest,
                    'breedTypeId'  => $breedTypeId,
                    'female'  => $femaleQuantity+ $totalExtenedFemaleQuantityLatest,
                ];
                $stockModel->insert($stock);
            } else {
                $stock = [
                    'male'  => $maleQuantity + $totalExtenedMaleQuantityLatest +  $uniqueStock->male,
                    'entryDate' => $this->request->getVar('arrivalDate'),
                    'entryDateBs' => $this->request->getVar('arrivalDateBs'),
                    'initialAge' => $this->request->getVar('arrivalAge'),
                    'entryMale' => $maleQuantity + $totalExtenedMaleQuantityLatest,
                    'entryFemale' => $femaleQuantity + $totalExtenedFemaleQuantityLatest,
                    'breedTypeId'  => $breedTypeId,
                    'female'  => $femaleQuantity+ $totalExtenedFemaleQuantityLatest + $uniqueStock->female,
                ];
                $stockModel->update($uniqueStock->id, $stock);
            }
            $model->update($id, $data);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'Main Entry Updated Successfully'
            ];
        }
        return $this->respond($response);
    }
    public function getEntryData()
    {
        $shedId = $this->request->getVar('shedId');
        $id = $this->request->getVar('id');
        $mainEntry = new MainEntry();
        $extenedMainEntry = new ExtendedMainEntry();
        $singleMainEntry = $mainEntry->where('id', $id)->first();
        $extendedMainEntryData = $extenedMainEntry->where('mainEntryId', $id)->get()->getResult();
        $response = [
            'status' => 200,
            'error' => null,
            'id' => $id,
        ];
        return $this->respond($response);
    }

    public function addUpdateDailyEntry()
    {
        $dailyEntryModel = new DailyEntry();
        $dailyMedVacModel = new DailyMedicineVaccine();
        $dailyRemarksModel = new DailyRemark();
        $stockModel = new Stock();
        $mainEntryModel = new MainEntry();

        $shedId = $this->request->getVar('shedId');
        $date = $this->request->getVar('date');

        $cullingMale = $this->request->getVar('cullingMale');
        $cullingFemale = $this->request->getVar('cullingFemale');
        $mortalityMale = $this->request->getVar('mortalityMale');
        $mortalityFemale = $this->request->getVar('mortalityFemale');

        $male = 0;
        $female = 0;
        $lot = 0;

        $maleToBeDeductedFromStock = 0;
        $femaleToBeDeductedFromStock = 0;
        if (!empty($cullingMale)) {
            $maleToBeDeductedFromStock += $cullingMale;
        }
        if (!empty($mortalityMale)) {
            $maleToBeDeductedFromStock += $mortalityMale;
        }
        if (!empty($cullingFemale)) {
            $femaleToBeDeductedFromStock += $cullingFemale;
        }
        if (!empty($mortalityFemale)) {
            $femaleToBeDeductedFromStock += $mortalityFemale;
        }
        $where = "shedId = ".$shedId." AND (male > 0 OR female > 0)";
        $stockByShedId = $stockModel->where($where)->first();
        if (!empty($stockByShedId)) {
            $mainEntryDate = Time::parse($stockByShedId->entryDate);
            $dailyEntryDate = Time::parse($date);
            if (!($mainEntryDate->isBefore($dailyEntryDate))) {
                return $this->failForbidden('Daily entry date is before the date that is entered in stock');
            }
            $male = $stockByShedId->male;
            $female = $stockByShedId->female;
            $lot = $stockByShedId->lot;
        } else {
            return $this->failForbidden('Selected shed has no Chickens in Stock');
        }
        $entryData = [
            'shedId'  => $shedId,
            'lot'  => $lot,
            'date'  => $date,
            'dateBs'  => $this->request->getVar('dateBs'),
            'morningInTemp'  => $this->request->getVar('morningInTemp'),
            'morningOutTemp'  => $this->request->getVar('morningOutTemp'),
            'eveningInTemp'  => $this->request->getVar('eveningInTemp'),
            'eveningOutTemp'  => $this->request->getVar('eveningOutTemp'),
            'morningInHumidity'  => $this->request->getVar('morningInHumidity'),
            'morningOutHumidity'  => $this->request->getVar('morningOutHumidity'),
            'eveningInHumidity'  => $this->request->getVar('eveningInHumidity'),
            'eveningOutHumidity'  => $this->request->getVar('eveningOutHumidity'),
            'totalEggProduction'  => $this->request->getVar('totalEggProduction'),
            'brokenEggCount'  => $this->request->getVar('brokenEggCount'),
            'nhe'  => $this->request->getVar('nhe'),
            'std'  => $this->request->getVar('std'),
            'percent'  => $this->request->getVar('percent'),
            'avgEggWeight'  => $this->request->getVar('avgEggWeight'),
            'lightStart'  => $this->request->getVar('lightStart'),
            'lightOut'  => $this->request->getVar('lightOut'),
            'lightLux'  => $this->request->getVar('lightLux'),
            'lightTime'  => $this->request->getVar('lightTime'),
            'feedMale'  => $this->request->getVar('feedMale'),
            'feedFemale'  => $this->request->getVar('feedFemale'),
            'feedTypeId'  => $this->request->getVar('feedTypeId'),
            'weightMale'  => $this->request->getVar('weightMale'),
            'weightFemale'  => $this->request->getVar('weightFemale'),
            'mortalityMale'  => $mortalityMale,
            'mortalityFemale'  => $mortalityFemale,
            'cullingMale'  => $cullingMale,
            'cullingFemale'  => $cullingFemale,
            'male'  => $male,
            'female'  => $female,
            'description'  => $this->request->getVar('description'),
            'coolingPad1'  => $this->request->getVar('coolingPad1'),
            'coolingPad2'  => $this->request->getVar('coolingPad2'),
            'coolingPad3'  => $this->request->getVar('coolingPad3'),
            'water'  => $this->request->getVar('water'),
            'fan'  => $this->request->getVar('fan'),
            'feedingTrolly'  => $this->request->getVar('feedingTrolly'),
            'screeper'  => $this->request->getVar('screeper'),
            'conveyer'  => $this->request->getVar('conveyer'),
        ];

        $medicineVaccineData = $this->request->getVar('medicineVaccine');


        $whereClauseArrayForDailyEntry = array('shedId' =>  $shedId, 'date' => $date);
        $uniqueDailyEntry = $dailyEntryModel->where($whereClauseArrayForDailyEntry)->first();
        $id = 0;
        if (empty($uniqueDailyEntry)) {
            if ($male < $maleToBeDeductedFromStock || $female < $femaleToBeDeductedFromStock) {
                return $this->failForbidden('Please check culling or mortality. Data exceeds stock');
            }
            $id = $dailyEntryModel->insert($entryData);
            $mainEntryData = $mainEntryModel->getRecentlyAddedMainEntryByShedIdAndLot($shedId, $lot);
            if (!empty($mainEntryData)) {
                $mainEntryStatus = [
                    'status' => 1
                ];
                foreach ($mainEntryData as $item) {
                    $mainEntryModel->update($item->id, $mainEntryStatus);
                }
            }
            $stock = [
                'male'  => $male - $maleToBeDeductedFromStock,
                'female'  => $female - $femaleToBeDeductedFromStock,
            ];
            $stockModel->update($stockByShedId->id, $stock);
        } else {
            $id = $uniqueDailyEntry->id;
            //old data should be increamented
            $maleToBeIncremented = $uniqueDailyEntry->mortalityMale + $uniqueDailyEntry->cullingMale;
            $femaleToBeIncremented = $uniqueDailyEntry->mortalityFemale + $uniqueDailyEntry->cullingFemale;
            if (($male + $maleToBeIncremented) < $maleToBeDeductedFromStock || ($female + $femaleToBeIncremented) < $femaleToBeDeductedFromStock) {
                return $this->failForbidden('Please check culling or mortality. Data exceeds stock');
            }
            $stock = [
                'male'  =>   $male - $maleToBeDeductedFromStock + $maleToBeIncremented,
                'female'  =>  $female - $femaleToBeDeductedFromStock + $femaleToBeIncremented,
            ];

            if ($male < $maleToBeDeductedFromStock || $female < $femaleToBeDeductedFromStock) {
                return $this->failForbidden('Please check culling or mortality. Data exceeds stock');
            }

            $stockModel->update($stockByShedId->id, $stock);
            $dailyEntryModel->update($id, $entryData);
        }

        $medVacToBeInserted = array();
        foreach ($medicineVaccineData as $item) {
            if (!empty($item->medicinevaccineId)) {
                array_push($medVacToBeInserted, array(
                    'medicinevaccineId' => $item->medicinevaccineId,
                    'dailyEntryId' => $id,
                    'quantity' => $item->quantity
                ));
            }
        }

        if (!empty($medVacToBeInserted)) {
            $dailyMedVacModel->where('dailyEntryId',$id)->delete();
            $dailyMedVacModel->insertBatch($medVacToBeInserted);
        }

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'Daily Entry Added Successfully'
        ];
        return $this->respond($response);
    }


    public function convert_num_to($date = '2019-12-29', $to = 'nep', $font_nep = '0') // date must be Y-m-d format
    {
        $to_date = explode('-', $date);
        $yy = ltrim($to_date[0], '0');
        $mm = ltrim($to_date[1], '0');
        $dd = ltrim($to_date[2], '0');
        // Data for nepali date
        $bs = array(
            0 => array(2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            1 => array(2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            2 => array(2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            3 => array(2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            4 => array(2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            5 => array(2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            6 => array(2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            7 => array(2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            8 => array(2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
            9 => array(2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            10 => array(2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            11 => array(2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            12 => array(2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
            13 => array(2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            14 => array(2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            15 => array(2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            16 => array(2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
            17 => array(2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            18 => array(2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            19 => array(2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            20 => array(2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            21 => array(2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            22 => array(2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
            23 => array(2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            24 => array(2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            25 => array(2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            26 => array(2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            27 => array(2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            28 => array(2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            29 => array(2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
            30 => array(2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            31 => array(2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            32 => array(2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            33 => array(2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            34 => array(2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            35 => array(2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
            36 => array(2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            37 => array(2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            38 => array(2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            39 => array(2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
            40 => array(2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            41 => array(2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            42 => array(2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            43 => array(2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
            44 => array(2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            45 => array(2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            46 => array(2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            47 => array(2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            48 => array(2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            49 => array(2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
            50 => array(2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            51 => array(2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            52 => array(2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            53 => array(2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
            54 => array(2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            55 => array(2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            56 => array(2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30),
            57 => array(2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            58 => array(2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            59 => array(2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            60 => array(2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            61 => array(2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            62 => array(2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31),
            63 => array(2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            64 => array(2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            65 => array(2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            66 => array(2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31),
            67 => array(2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            68 => array(2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            69 => array(2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            70 => array(2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30),
            71 => array(2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            72 => array(2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30),
            73 => array(2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31),
            74 => array(2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            75 => array(2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            76 => array(2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
            77 => array(2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31),
            78 => array(2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30),
            79 => array(2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30),
            80 => array(2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30),
            81 => array(2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30),
            82 => array(2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
            83 => array(2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
            84 => array(2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30),
            85 => array(2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30),
            86 => array(2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
            87 => array(2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30),
            88 => array(2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30),
            89 => array(2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30),
            90 => array(2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30)
        );
        $nep_date = array('year' => '', 'month' => '', 'date' => '', 'day' => '', 'nmonth' => '', 'num_day' => '');
        $eng_date = array('year' => '', 'month' => '', 'date' => '', 'day' => '', 'emonth' => '', 'num_day' => '');
        $debug_info = "";
        $eng_week_day = array('1' => 'Sunday', '2' => 'Monday', '3' => 'Tuesday', '4' => 'Wednesday', '5' => 'Thursday', '6' => 'Friday', '7' => 'Saturday');
        $eng_month = array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        $nep_week_day = array('1' => 'आइतवार', '2' => 'सोमवार', '3' => 'मङ्गलवार', '4' => 'बुधवार', '5' => 'बिहिवार', '6' => 'शुक्रवार', '7' => 'शनिवार');
        $nep_month = array('1' => 'बैशाख', '2' => 'जेष्ठ', '3' => 'असार', '4' => 'श्रावण', '5' => 'भदौ', '6' => 'आश्विन', '7' => 'कार्तिक', '8' => 'मंसिर', '9' => 'पुष', '10' => 'माघ', '11' => 'फाल्गुन', '12' => 'चैत्र');
        $nep_month_eng = array('1' => 'Baishak', '2' => 'Jesta', '3' => 'Ashar', '4' => 'Sharwan', '5' => 'Bhadra', '6' => 'Ashwin', '7' => 'Kartik', '8' => 'Mangsir', '9' => 'Poush', '10' => 'Magh', '11' => 'Falgun', '12' => 'Chaitra');
        $eng_range = array(1944, 2033, 1, 12, 1, 31);
        $nep_range = array(2000, 2089, 1, 12, 1, 32);
        if ($to == 'nep') {
            //            echo $yy.'|'.$mm.'|'.$dd.'<br/>';
            //            if ($yy>=$eng_range[0] && $yy<=$eng_range[1]) {
            //                echo 'HERE-GOOD';exit;
            //            }
            if (($yy >= $eng_range[0] && $yy <= $eng_range[1]) && ($mm >= $eng_range[2] && $mm <= $eng_range[3]) && ($dd >= $eng_range[4] && $dd <= $eng_range[5])) {
                $month  = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                $lmonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                $def_eyy     = 1944; // initial english date.
                $def_nyy     = 2000;
                $def_nmm     = 9;
                $def_ndd     = 17 - 1; // inital nepali date.
                $total_eDays = 0;
                $total_nDays = 0;
                $a           = 0;
                $day         = 7 - 1;
                $m           = 0;
                $y           = 0;
                $i           = 0;
                $j           = 0;
                $numDay      = 0;
                // Count total no. of days in-terms year
                for ($i = 0; $i < ($yy - $def_eyy); $i++) { // total days for month calculation...(english)
                    $check_leap = $def_eyy + $i;
                    if ((($check_leap % 100 == 0) && ($check_leap % 400 == 0)) || (($check_leap % 100 != 0) && ($check_leap % 4 == 0))) {
                        for ($j = 0; $j < 12; $j++) {
                            $total_eDays += $lmonth[$j];
                        }
                    } else {
                        for ($j = 0; $j < 12; $j++) {
                            $total_eDays += $month[$j];
                        }
                    }
                }
                // Count total no. of days in-terms of month
                for ($i = 0; $i < ($mm - 1); $i++) {
                    $check_leap = $yy;
                    if ((($check_leap % 100 == 0) && ($check_leap % 400 == 0)) || (($check_leap % 100 != 0) && ($check_leap % 4 == 0))) {
                        $total_eDays += $lmonth[$i];
                    } else {
                        $total_eDays += $month[$i];
                    }
                }
                // Count total no. of days in-terms of date
                $total_eDays += $dd;
                $i           = 0;
                $j           = $def_nmm;
                $total_nDays = $def_ndd;
                $m           = $def_nmm;
                $y           = $def_nyy;
                // Count nepali date from array
                while ($total_eDays != 0) {
                    $a = $bs[$i][$j];
                    $total_nDays++; //count the days
                    $day++; //count the days interms of 7 days
                    if ($total_nDays > $a) {
                        $m++;
                        $total_nDays = 1;
                        $j++;
                    }
                    if ($day > 7) {
                        $day = 1;
                    }
                    if ($m > 12) {
                        $y++;
                        $m = 1;
                    }
                    if ($j > 12) {
                        $j = 1;
                        $i++;
                    }
                    $total_eDays--;
                }
                $numDay = $day;
                $result['year']     = $y;
                $result['month']    = sprintf("%02d", $m);
                $result['date']     = sprintf("%02d", $total_nDays);
                $result['wmonth']   = $nep_month_eng[$m];
                $result['day']      = $eng_week_day[$day];
                // if ($font_nep == '1') {
                //     // $result['year']     = $this->convert_no($y);
                //     // $result['month']    = $this->convert_no($m);
                //     // $result['date']     = $this->convert_no($total_nDays);
                //     // $result['wmonth']   = $nep_month[$m];
                //     // $result['day']      = $nep_week_day[$day];
                // } elseif ($font_nep == '0') {
                //     $result['year']     = $y;
                //     $result['month']    = sprintf("%02d", $m);
                //     $result['date']     = sprintf("%02d", $total_nDays);
                //     $result['wmonth']   = $nep_month_eng[$m];
                //     $result['day']      = $eng_week_day[$day];
                // }
                $result['nmonth']   = $numDay;
                return $result['year'] . '-' . $result['month'] . '-' . $result['date'];
            }
        } elseif ($to == 'eng') {
            if (($yy >= $nep_range[0] && $yy <= $nep_range[1]) && ($mm >= $nep_range[2] && $mm <= $nep_range[3]) && ($dd >= $nep_range[4] && $dd <= $nep_range[5])) {
                $def_eyy     = 1943;
                $def_emm     = 4;
                $def_edd     = 14 - 1; // initial english date.
                $def_nyy     = 2000;
                $def_nmm     = 1;
                $def_ndd     = 1; // iniital equivalent nepali date.
                $total_eDays = 0;
                $total_nDays = 0;
                $a           = 0;
                $day         = 4 - 1;
                $m           = 0;
                $y           = 0;
                $i           = 0;
                $k           = 0;
                $numDay      = 0;
                $month  = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                $lmonth = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                // Count total days in-terms of year
                for ($i = 0; $i < ($yy - $def_nyy); $i++) {
                    for ($j = 1; $j <= 12; $j++) {
                        $total_nDays += $bs[$k][$j];
                    }
                    $k++;
                }
                // Count total days in-terms of month
                for ($j = 1; $j < $mm; $j++) {
                    $total_nDays += $bs[$k][$j];
                }
                // Count total days in-terms of dat
                $total_nDays += $dd;
                // Calculation of equivalent english date...
                $total_eDays = $def_edd;
                $m           = $def_emm;
                $y           = $def_eyy;
                while ($total_nDays != 0) {
                    $check_leap = $y;
                    if ((($check_leap % 100 == 0) && ($check_leap % 400 == 0)) || (($check_leap % 100 != 0) && ($check_leap % 4 == 0))) {
                        $a = $lmonth[$m];
                    } else {
                        $a = $month[$m];
                    }
                    $total_eDays++;
                    $day++;
                    if ($total_eDays > $a) {
                        $m++;
                        $total_eDays = 1;
                        if ($m > 12) {
                            $y++;
                            $m = 1;
                        }
                    }
                    if ($day > 7) {
                        $day = 1;
                    }
                    $total_nDays--;
                }
                $numDay = $day;
                $result['year']     = $y;
                $result['month']    = sprintf("%02d", $m);
                $result['date']     = sprintf("%02d", $total_eDays);
                $result['wmonth']   = $eng_month[$m];
                $result['day']      = $eng_week_day[$day];
                $result['nmonth']   = $eng_month[$m];
                $result['nmonth']   = $numDay;
                return $result;
            }
        }
        return '-';
    }
}
