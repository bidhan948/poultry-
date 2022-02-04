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


class Excel extends ResourceController
{
    use ResponseTrait;

    // all entries
    public function saveDailyData()
    {
          
        $dailyEntryModel = new DailyEntry();
      
         $dailyMedVacModel = new DailyMedicineVaccine();
        $stockModel = new Stock();
        $mainEntryModel = new MainEntry();
        
       
        $shedId = $this->request->getVar('shedId');
        $lot = (int)$this->request->getVar('lot');
 
        $file = $this->request->getFile('file');
        
         
        // return $this->respond($file);
        if (empty($shedId)) {
            return $this->failForbidden( 'Shed is required');
        }
        if (empty($lot)) {
            return $this->failForbidden('Lot Number is required');
        }
        if (empty($file)) {
            return $this->failForbidden( 'Please select file');
        }
        // $dailyEntry =  $dailyEntryModel->where('shedId', $shedId)->where('lot', $lot)->first();
        // if (!empty($dailyEntry)) {
        //     return $this->failForbidden(400, 'Daily data for selected shed and lot already exists');
        // }
        
          

        $excelLib = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $objPHPExcel = $excelLib->load($file);
        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $arrayCount = count($allDataInSheet);
        
        



        $transferModel = new \App\Models\StockTransfer();
        $transferDetailModel = new \App\Models\StockTransferDetail();




        $stockByShedId = $stockModel->where('shedId', $shedId)->where('lot', $lot)->first();
        if (empty($stockByShedId)) {

            return $this->failForbidden('Selected shed has no Chickens in Stock');
        }

        $transferDataObject = $transferModel->getTransferFrom($stockByShedId->shedId, $stockByShedId->lot);

        $transferDetailObject = $transferDetailModel->getTransferTo($stockByShedId->entrySource, $stockByShedId->shedId, $stockByShedId->lot);
      
        $male = $stockByShedId->entryMale;
        $female = $stockByShedId->entryFemale;

        $stockMale = $stockByShedId->male;
        $stockFemale = $stockByShedId->female;


        $isDeductedTransfer = false;
        $isIncrementedTransfer = false;
        $isAfterDeductedTransfer = false;
        $isAfterIncrementedTransfer = false;

        $testCullingMale = 0;
        $testMortalityMale = 0;
        $testMortalityFemale = 0;
        $testCullingFemale = 0;
        
        //validation
        $lastDate = '';
        
        for ($i = 2; $i <= $arrayCount; $i++) {

            $date_string = trim($allDataInSheet[$i]["A"]);
            

            if (!empty($date_string)) {
              
                $lastDate = $date_string;
                $tempDateAd =  Time::parse($date_string);
                $tempDateAdString =  $tempDateAd->toDateString();
                $tempDateAd =  Time::parse($tempDateAdString);
                $dateAd = $tempDateAd->format('Y-m-d');
                $dateBs = $this->convert_num_to($dateAd);
                //  echo $dateBs;die();

                $cullingMale = trim($allDataInSheet[$i]["AG"]);
                $cullingFemale = trim($allDataInSheet[$i]["AH"]);
                $mortalityMale = trim($allDataInSheet[$i]["AE"]);
                $mortalityFemale = trim($allDataInSheet[$i]["AF"]);
                $maleToBeDeductedFromStock = 0;
                $femaleToBeDeductedFromStock = 0;
                if (!empty($cullingMale)) {
                    $maleToBeDeductedFromStock += $cullingMale;
                    $testCullingMale += $cullingMale;
                }
                if (!empty($mortalityMale)) {
                    $maleToBeDeductedFromStock += $mortalityMale;
                    $testMortalityMale += $mortalityMale;
                }
                if (!empty($cullingFemale)) {
                    $femaleToBeDeductedFromStock += $cullingFemale;
                    $testCullingFemale += $cullingFemale;
                }
                if (!empty($mortalityFemale)) {
                    $femaleToBeDeductedFromStock += $mortalityFemale;
                    $testMortalityFemale  += $mortalityFemale;
                }

                $maleTransferFrom = 0;
                $femaleTransferFrom = 0;
                $maleTransferTo = 0;
                $femaleTransferTo = 0;

                foreach ($transferDataObject as $singleTransferData) {
                    $transferDate = $singleTransferData->date;
                    $tempTransferDate =  Time::parse($transferDate);
                    if ($tempDateAd->equals($tempTransferDate)) {
                        $maleTransferFrom -= $singleTransferData->male;
                        $femaleTransferFrom -= $singleTransferData->female;
                    }
                }

                foreach ($transferDetailObject as $singleTransferDetailsData) {
                    $transferDate = $singleTransferDetailsData->date;
                    $tempTransferDate =  Time::parse($transferDate);
                    if ($tempDateAd->equals($tempTransferDate)) {
                        $maleTransferTo += $singleTransferDetailsData->male;
                        $femaleTransferTo += $singleTransferDetailsData->female;
                    }
                }

                $male = $male + $maleTransferTo +  $maleTransferFrom;
                $female =  $female + $femaleTransferTo + $femaleTransferFrom;


                $whereClauseArrayForDailyEntry = array('shedId' =>  $shedId, 'date' => $dateAd);
                $uniqueDailyEntry = $dailyEntryModel->where($whereClauseArrayForDailyEntry)->first();
                $id = 0;
                if (empty($uniqueDailyEntry)) {
                    if ($male < $maleToBeDeductedFromStock || $female < $femaleToBeDeductedFromStock) {
                        return $this->failForbidden('Please check culling or mortality. Data exceeds stock');
                    }
                    $male  = $male - $maleToBeDeductedFromStock;
                    $female  = $female - $femaleToBeDeductedFromStock;
                } else {
                    $maleToBeIncremented = $uniqueDailyEntry->mortalityMale + $uniqueDailyEntry->cullingMale;
                    $femaleToBeIncremented = $uniqueDailyEntry->mortalityFemale + $uniqueDailyEntry->cullingFemale;
                    if (($male + $maleToBeIncremented) < $maleToBeDeductedFromStock || ($female + $femaleToBeIncremented) < $femaleToBeDeductedFromStock) {
                        
                        return $this->failForbidden('Please check culling or mortality. Data exceeds');
                    }
                    $male  = $male - $maleToBeDeductedFromStock + $maleToBeIncremented;
                    $female  = $female - $femaleToBeDeductedFromStock + $femaleToBeIncremented;
                }
            }
        }
       

        // $maleTransferFrom = 0;
        // $femaleTransferFrom = 0;
        // $maleTransferTo = 0;
        // $femaleTransferTo = 0;

        // if (!empty($lastDate)) {
        //     $tempDateAd =  Time::parse($lastDate);
        //     $tempDateAdString =  $tempDateAd->toDateString();
        //     $tempDateAd =  Time::parse($tempDateAdString);
        //     foreach ($transferDataObject as $singleTransferData) {
        //         $transferDate = $singleTransferData->date;
        //         $tempTransferDate =  Time::parse($transferDate);
        //         if ($tempDateAd->isBefore($tempTransferDate)) {
        //             $maleTransferFrom -= $singleTransferData->male;
        //             $femaleTransferFrom -= $singleTransferData->female;
        //         }
        //     }

        //     foreach ($transferDetailObject as $singleTransferDetailsData) {
        //         $transferDate = $singleTransferData->date;
        //         $tempTransferDate =  Time::parse($transferDate);
        //         if ($tempDateAd->isBefore($tempTransferDate)) {
        //             $maleTransferTo += $singleTransferDetailsData->male;
        //             $femaleTransferTo += $singleTransferDetailsData->female;
        //         }
        //     }
        // }

        // $response = [
        //     'status'   => 200,
        //     'error'    => null,
        //     'messages' => 'Daily Entry Added Successfully'
        // ];
        // return $this->respond($response);


        $male = $stockByShedId->entryMale;
        $female = $stockByShedId->entryFemale;

        //insert
        for ($i = 2; $i <= $arrayCount; $i++) {
              $date_string = trim($allDataInSheet[$i]["A"]);
            if (!empty($date_string)) {

                $tempDateAd =  Time::parse($date_string);
                $dateAd = $tempDateAd->format('Y-m-d');
                $dateBs = $this->convert_num_to($dateAd);

                $cullingMale = trim($allDataInSheet[$i]["AG"]);
                $cullingFemale = trim($allDataInSheet[$i]["AH"]);
                $mortalityMale = trim($allDataInSheet[$i]["AE"]);
                $mortalityFemale = trim($allDataInSheet[$i]["AF"]);


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
                //  echo $dateBs;die();
                $maleTransferFrom = 0;
                $femaleTransferFrom = 0;
                $maleTransferTo = 0;
                $femaleTransferTo = 0;

                foreach ($transferDataObject as $singleTransferData) {
                    $transferDate = $singleTransferData->date;
                    $tempTransferDate =  Time::parse($transferDate);
                    if ($tempDateAd->equals($tempTransferDate)) {
                        $maleTransferFrom -= $singleTransferData->male;
                        $femaleTransferFrom -= $singleTransferData->female;
                    }
                }

                foreach ($transferDetailObject as $singleTransferDetailsData) {
                    $transferDate = $singleTransferDetailsData->date;
                    $tempTransferDate =  Time::parse($transferDate);
                    if ($tempDateAd->equals($tempTransferDate)) {
                        $maleTransferTo += $singleTransferDetailsData->male;
                        $femaleTransferTo += $singleTransferDetailsData->female;
                    }
                }

                $male = $male + $maleTransferTo +  $maleTransferFrom;
                $female =  $female + $femaleTransferTo + $femaleTransferFrom;


                $entryData = [
                    'shedId'  => $shedId,
                    'lot'  => $lot,
                    'date'  => $dateAd,
                    'dateBs'  => $dateBs,
                    'morningInTemp'  => trim($allDataInSheet[$i]["C"]),
                    'morningOutTemp'  => trim($allDataInSheet[$i]["D"]),
                    'eveningInTemp'  =>  trim($allDataInSheet[$i]["M"]),
                    'eveningOutTemp'  => trim($allDataInSheet[$i]["N"]),
                    'morningInHumidity'  => trim($allDataInSheet[$i]["E"]),
                    'morningOutHumidity'  => trim($allDataInSheet[$i]["F"]),
                    'eveningInHumidity'  => trim($allDataInSheet[$i]["O"]),
                    'eveningOutHumidity'  => trim($allDataInSheet[$i]["P"]),
                    'totalEggProduction' => trim($allDataInSheet[$i]["Q"]),
                    'brokenEggCount' => trim($allDataInSheet[$i]["R"]),
                    'percent' => trim($allDataInSheet[$i]["I"]),
                    'std' => trim($allDataInSheet[$i]["J"]),
                    'nhe' => trim($allDataInSheet[$i]["U"]),
                    'avgEggWeight' => trim($allDataInSheet[$i]["L"]),
                    'lightTime' => trim($allDataInSheet[$i]["W"]),
                    'lightOut' => trim($allDataInSheet[$i]["X"]),
                    'lightLux' => trim($allDataInSheet[$i]["Y"]),
                    'feedMale' => trim($allDataInSheet[$i]["Z"]),
                    'feedFemale' => trim($allDataInSheet[$i]["AA"]),
                    'feedTypeId' => trim($allDataInSheet[$i]["AB"]),
                    'weightMale' => trim($allDataInSheet[$i]["AC"]),
                    'weightFemale' => trim($allDataInSheet[$i]["AD"]),
                    'mortalityMale' => trim($allDataInSheet[$i]["AE"]),
                    'mortalityFemale' => trim($allDataInSheet[$i]["AF"]),
                    'cullingMale' => trim($allDataInSheet[$i]["AG"]),
                    'cullingFemale' => trim($allDataInSheet[$i]["AH"]),
                    'male'  => $male,
                    'female'  => $female,
                    'description'  => '',
                ];

                $whereClauseArrayForDailyEntry = array('shedId' =>  $shedId, 'date' => $dateAd);
                $uniqueDailyEntry = $dailyEntryModel->where($whereClauseArrayForDailyEntry)->first();
                $id = 0;
                if (empty($uniqueDailyEntry)) {
                    if ($male < $maleToBeDeductedFromStock || $female < $femaleToBeDeductedFromStock) {
                        return $this->failForbidden('Please check culling or mortality. Data exceeds stock');
                    }


                    $id = $dailyEntryModel->insert($entryData);
                    $mainEntryData = $mainEntryModel->getRecentlyAddedOrActiveMainEntryByShedIdAndLot($shedId, $lot);
                    if (!empty($mainEntryData)) {
                        $mainEntryStatus = [
                            'status' => 1
                        ];
                        if (($male - $maleToBeDeductedFromStock) <= 0 && ($female - $femaleToBeDeductedFromStock) <= 0) {
                            $mainEntryStatus = [
                                'status' => 2
                            ];
                        }

                        foreach ($mainEntryData as $item) {
                            $mainEntryModel->update($item->id, $mainEntryStatus);
                        }
                    }
                    $male  = $male - $maleToBeDeductedFromStock;
                    $female  = $female - $femaleToBeDeductedFromStock;
                } else {
                    $id = $uniqueDailyEntry->id;
                    $maleToBeIncremented = $uniqueDailyEntry->mortalityMale + $uniqueDailyEntry->cullingMale;
                    $femaleToBeIncremented = $uniqueDailyEntry->mortalityFemale + $uniqueDailyEntry->cullingFemale;

                    $mainEntryData = $mainEntryModel->getAllMainEntryByShedIdAndLot($shedId, $lot);
                    if (!empty($mainEntryData)) {
                        $mainEntryStatus = [
                            'status' => 1
                        ];

                        if ((($male + $maleToBeIncremented) - $maleToBeDeductedFromStock) <= 0 && (($female + $femaleToBeIncremented) - $femaleToBeDeductedFromStock) <= 0) {
                            $mainEntryStatus = [
                                'status' => 2
                            ];
                        }

                        foreach ($mainEntryData as $item) {
                            $mainEntryModel->update($item->id, $mainEntryStatus);
                        }
                    }
                    $male  = $male - $maleToBeDeductedFromStock + $maleToBeIncremented;
                    $female  = $female - $femaleToBeDeductedFromStock + $femaleToBeIncremented;
                    $dailyEntryModel->update($id, $entryData);
                }
               
                // $medVacToBeInserted = array();
                // foreach ($medicineVaccineData as $item) {
                //     if (!empty($item->vaccineId)) {
                //         array_push($medVacToBeInserted, array(
                //             'medicinevaccineId' => $item->vaccineId,
                //             'dailyEntryId' => $id,
                //             'quantity' => $item->quantity
                //         ));
                //     }
                //     if (!empty($item->medicineId)) {
                //         array_push($medVacToBeInserted, array(
                //             'medicinevaccineId' => $item->medicineId,
                //             'dailyEntryId' => $id,
                //             'quantity' => $item->quantity
                //         ));
                //     }
                // }
                // if (!empty($remarksToBeInserted)) {
                //     $dailyMedVacModel->delete(['dailyEntryId' => $id]);
                //     $dailyMedVacModel->insertBatch($medVacToBeInserted);
                // }
            }
        }

        // $maleTransferFrom = 0;
        // $femaleTransferFrom = 0;
        // $maleTransferTo = 0;
        // $femaleTransferTo = 0;

        // if (!empty($lastDate)) {
        //     $tempDateAd =  Time::parse($lastDate);
        //     $tempDateAdString =  $tempDateAd->toDateString();
        //     $tempDateAd =  Time::parse($tempDateAdString);
        //     foreach ($transferDataObject as $singleTransferData) {
        //         $transferDate = $singleTransferData->date;
        //         $tempTransferDate =  Time::parse($transferDate);
        //         if ($tempDateAd->isBefore($tempTransferDate)) {
        //             $maleTransferFrom -= $singleTransferData->male;
        //             $femaleTransferFrom -= $singleTransferData->female;
        //         }
        //     }

        //     foreach ($transferDetailObject as $singleTransferDetailsData) {
        //         $transferDate = $singleTransferDetailsData->date;
        //         $tempTransferDate =  Time::parse($transferDate);
        //         if ($tempDateAd->isBefore($tempTransferDate)) {
        //             $maleTransferTo += $singleTransferDetailsData->male;
        //             $femaleTransferTo += $singleTransferDetailsData->female;
        //         }
        //     }
        // }

        $stock = [
            'male'  => $male,
            'female'  => $female,
        ];
        $stockModel->update($stockByShedId->id, $stock);

        
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
