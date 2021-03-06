<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class FarmReport extends Model
{

    public function getFarmReport($pageIndex, $pageSize, $fromDate, $toDate, $group, $shed, $lot)
    {

        $shedFilterString = "";
        $shedFilterStringFirst = "";

        // this is from date between search
        $fromDate = "";
        $toDate = "";
        $date = "";
        // if (!empty($fromDate) && !empty($toDate)) {
        //     $date = " where s.date > . ' $fromDate' . AND s.date < .$toDate.";
        // }
        // end of date between search
        if ((!empty($shed) && empty($group)) || !empty($shed) && !empty($group)) {

            $shedFilterString = " s.shedId = " . $shed;
            $shedFilterStringFirst = " s.shedId = " . $shed;
            if (!empty($dateFilterString)) {
                $shedFilterString = " AND s.shedId = " . $shed;
            }
        }
        if (!empty($group) && empty($shed)) {
            $shedModel = new \App\Models\Shed();
            $sheds =  $shedModel->where('groupId', $group)->get()->getResult();
            if (!empty($sheds)) {
                $filterString = "(";
                foreach ($sheds as $i => $item) {
                    if ($i == 0) {
                        $filterString .= $item->id;
                    } else {
                        $filterString .= "," . $item->id;
                    }
                }
                $filterString .= ")";
                $shedFilterString = " s.shedId IN " . $filterString;
                $shedFilterStringFirst = " s.shedId IN " . $filterString;
                if (!empty($dateFilterString)) {
                    $shedFilterString = " AND s.shedId IN " . $filterString;
                }
            }
        }
        $lotFilterSting = "";
        if (!empty($lot)) {
            $lotFilterSting = " s.lot = " . $lot;

            if (!empty($dateFilterString) || !empty($shedFilterString)) {
                $lotFilterSting = " AND s.lot = " . $lot;
            }
        }
        $whereClause = "";
        if (!empty($lotFilterSting) || !empty($shedFilterStringFirst)) {
            $whereClause = " WHERE ";
        }
        $stocksDataString = "
                SELECT 
                s.entryDate as entryDate,
                s.entryMale as totalMale,
                s.entryFemale as totalFemale,
                s.entrySource as entrySource,
                s.initialAge as age,
                s.lot as lot,
                sh.name as shedName,
                sh.id as shedId
                FROM stock s
                INNER JOIN sheds sh ON sh.id = s.shedId
                 " . $whereClause . $shedFilterStringFirst . $lotFilterSting;


        $dataQuery = $this->db->query($stocksDataString);

        // your object result
        $data_object = $dataQuery->getResult();
        $report_array = array();
        $transferModel = new \App\Models\StockTransfer();
        $transferDetailModel = new \App\Models\StockTransferDetail();
        $extendedMainEntryModel = new \App\Models\ExtendedMainEntry();
        foreach ($data_object as $i => $item) {
            $queryString = "     
            SELECT 
                s.date as entryDate,
                s.dateBs as entryDateBs,
                sh.name as shedName,
                sh.id as shedId,
                s.lot as lot,
                DATEDIFF(s.date, '" . $item->entryDate . "') + " . $item->age . " as age,
                s.mortalityMale as mortalityMale,
                s.mortalityFemale as mortalityFemale,
                s.cullingMale as cullingMale,
                s.cullingFemale as cullingFemale,
                s.feedMale as feedMale,
                s.feedFemale as feedFemale,
                s.totalEggProduction as totalEggsProduction,
                s.nhe as nhe,
                s.water as water,
                s.eveningInTemp as eveningInTemp,
                s.eveningOutTemp as eveningOutTemp,
                s.brokenEggCount as brokenEggCount
                FROM dailyentries s
                LEFT JOIN sheds sh ON sh.id = s.shedId
                WHERE s.shedId =" . $item->shedId . " AND s.lot =" . $item->lot . "  ORDER BY s.date ";


            //return $queryString;
            //$queryString = "SELECT * FROM dailyentries " . $dateFilterString . $shedFilterString . $lotFilterSting . " ORDER BY dailyentries.id";
            $subQuery = $this->db->query($queryString);
            // $this->db->transCommit();
            // your object result

            $sub_object = $subQuery->getResult();




            $transferDataObject = $transferModel->getTransferFrom($item->shedId, $item->lot);
            $extendedMainEntryObject = $extendedMainEntryModel->getExtendedEntriesByShedAndLot($item->shedId, $item->lot);
            $transferDetailObject = $transferDetailModel->getTransferTo($item->entrySource, $item->shedId, $item->lot);


            $cumulativeMortalityMale = 0;
            $cumulativeMortalityFemale = 0;
            $cumulativeCullingMale = 0;
            $cumulativeCullingFemale = 0;

            $cumulativeTotalEggs = 0;
            $cumulativeTotalHeEggs = 0;
            $cumulativeFeedMale = 0;
            $cumulativeFeedFemale = 0;

            $totalMale = $item->totalMale;
            $totalFemale = $item->totalFemale;
            foreach ($sub_object as $i => $dailyData) {

                $mortalityMale = $dailyData->mortalityMale;
                $mortalityFemale = $dailyData->mortalityFemale;
                $cullingMale = $dailyData->cullingMale;
                $cullingFemale = $dailyData->cullingFemale;
                $feedMale =  round($dailyData->feedMale, 2);
                $feedFemale =  round($dailyData->feedFemale, 2);
                $totalEggProduction = $dailyData->totalEggsProduction;
                $brokenEggCount = $dailyData->brokenEggCount;
                $nhe = $dailyData->nhe;
                $he = $totalEggProduction - $nhe - $brokenEggCount;


                $maleTransferFrom = 0;
                $femaleTransferFrom = 0;
                $maleTransferTo = 0;
                $femaleTransferTo = 0;
                $maleEntry = 0;
                $femaleEntry = 0;

                $tempDateAd =  Time::parse($dailyData->entryDate);
                $dateAd = $tempDateAd->toDateString();

                foreach ($transferDataObject as $singleTransferData) {
                    if ($dateAd == $singleTransferData->date) {
                        $maleTransferFrom -= $singleTransferData->male;
                        $femaleTransferFrom -= $singleTransferData->female;
                    }
                }

                $tempDateAd =  Time::parse($dailyData->entryDate);
                $dateAd = $tempDateAd->toDateString();

                foreach ($transferDetailObject as $singleTransferDetailsData) {
                    if ($dateAd == $singleTransferDetailsData->date) {
                        $maleTransferTo += $singleTransferDetailsData->male;
                        $femaleTransferTo += $singleTransferDetailsData->female;
                    }
                }

                $tempDateAd =  Time::parse($dailyData->entryDate);
                $dateAd = $tempDateAd->toDateString();

                foreach ($extendedMainEntryObject as $singleMainEntryData) {
                    if ($dateAd == $singleMainEntryData->arrivalDate) {
                        $maleEntry += $singleMainEntryData->arrivalQuantityMale;
                        $femaleEntry += $singleMainEntryData->arrivalQuantityFemale;
                    }
                }

                $cumulativeMortalityMale += $mortalityMale;
                $cumulativeMortalityFemale += $mortalityFemale;

                $cumulativeFeedMale += $feedMale;
                $cumulativeFeedFemale += $feedFemale;

                $cumulativeCullingFemale += $cullingFemale;
                $cumulativeCullingMale += $cullingMale;

                $cumulativeTotalEggs += $totalEggProduction;
                $cumulativeTotalHeEggs += $he;
                $hePercent = 0;
                if ($totalEggProduction != 0)
                    $hePercent = round(($he / $totalEggProduction) * 100, 2);

                $totalMale = $totalMale + $maleTransferTo + $maleEntry +  $maleTransferFrom - $mortalityMale - $cullingMale;
                $totalFemale =  $totalFemale + $femaleTransferTo + $femaleEntry + $femaleTransferFrom - $mortalityFemale - $cullingFemale;



                $cumulativeDeplitionMale = $cumulativeMortalityMale + $cumulativeCullingMale;
                $cumulativeDeplitionFemale = $cumulativeMortalityFemale + $cumulativeCullingFemale;

                $henHouseNumber = round($cumulativeTotalHeEggs / $item->totalFemale, 4);


                $henDayProduction = $totalFemale == 0 ? 0 : round(($totalEggProduction / $totalFemale) * 100, 2);

                $cumulativeDeplitionMalePercent = $totalMale == 0 ? 0 : round(($cumulativeDeplitionMale / $totalMale) * 100, 2);
                $cumulativeDeplitionFemalePercent = $totalFemale == 0 ? 0 : round(($cumulativeDeplitionFemale / $totalFemale) * 100, 2);

                $mortalityMalePercent = $totalMale == 0 ? 0 : round(($mortalityMale / $totalMale) * 100, 2);
                $mortalityFemalePercent = $totalFemale == 0 ? 0 : round(($mortalityFemale / $totalFemale) * 100, 2);


                $hhhe = round($he / $item->totalFemale, 4);

                $single_result_array = [
                    'totalMale' => $totalMale,
                    'totalFemale' => $totalFemale,
                    'entryDate' => $dailyData->entryDate,
                    'entryDateBs' => $dailyData->entryDateBs,
                    'shedId' => $dailyData->shedId,
                    'shedName' => $item->shedName,
                    'lot' => $dailyData->lot,
                    'age' => $dailyData->age,
                    'water' => $dailyData->water,
                    'water_in_ml' => round($dailyData->water / 1000, 3),
                    'eveningInTemp' => $dailyData->eveningInTemp,
                    'eveningOutTemp' => $dailyData->eveningOutTemp,
                    'mortalityMale' => $mortalityMale,
                    'mortalityFemale' => $mortalityFemale,
                    'mortalityPercentMale' => $mortalityMalePercent,
                    'mortalityPercentFemale' => $mortalityFemalePercent,
                    'cumMortalityMale' => $cumulativeMortalityMale,
                    'cumMortalityFemale' => $cumulativeMortalityFemale,
                    'cullingMale' => $cullingMale,
                    'cullingFemale' => $cullingFemale,
                    'cumCullingMale' => $cumulativeCullingMale,
                    'cumCullingFemale' => $cumulativeCullingFemale,
                    'cumDeplitionMale' => $cumulativeDeplitionMale,
                    'cumDeplitionFemale' => $cumulativeDeplitionFemale,
                    'cumDeplitionPercentMale' => $cumulativeDeplitionMalePercent,
                    'cumDeplitionPercentFemale' => $cumulativeDeplitionFemalePercent,
                    'totalEggsProduction' => $totalEggProduction,
                    'he' => $he,
                    'nhe' => $nhe,
                    'brokenEggCount' => $brokenEggCount,
                    'cumTotalEggs' => $cumulativeTotalEggs,
                    'cumTotalHeEggs' => $cumulativeTotalHeEggs,
                    'hePercent' => $hePercent,
                    'henHouseNumber' => $henHouseNumber,
                    'henDayProduction' => $henDayProduction,
                    'feedMale' => $feedMale,
                    'feedFemale' => $feedFemale,
                    'cumfeedMale' => round($cumulativeFeedMale, 2),
                    'cumfeedFemale' => round($cumulativeFeedFemale, 2),
                    'transferOutMale' => $maleTransferFrom,
                    'transferOutFemale' => $femaleTransferFrom,
                    'transferInMale' => $maleTransferTo,
                    'transferInFemale' => $femaleTransferTo,
                    'hhhe' => $hhhe,
                    // 'count'  => $count_object[0]->count,
                    // 'pageIndex' => $pageIndex,
                    // 'pageSize' => $pageSize
                ];

                array_push($report_array, $single_result_array);
            }
        }

        if (empty($report_array)) {
            return $data_object;
        }
        return $report_array;
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
        $nep_week_day = array('1' => '??????????????????', '2' => '??????????????????', '3' => '????????????????????????', '4' => '??????????????????', '5' => '?????????????????????', '6' => '????????????????????????', '7' => '??????????????????');
        $nep_month = array('1' => '???????????????', '2' => '???????????????', '3' => '????????????', '4' => '??????????????????', '5' => '?????????', '6' => '??????????????????', '7' => '?????????????????????', '8' => '???????????????', '9' => '?????????', '10' => '?????????', '11' => '?????????????????????', '12' => '???????????????');
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
