<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyEntry extends Model
{

	protected $table                = 'dailyentries';
	protected $primaryKey           = 'id';
	protected $returnType           = 'object';
	protected $allowedFields        = [
		'shedId',
		'lot',
		'date',
		'dateBs',
		'morningInTemp',
		'morningOutTemp',
		'eveningInTemp',
		'eveningOutTemp',
		'morningInHumidity',
		'morningOutHumidity',
		'eveningInHumidity',
		'eveningOutHumidity',
		'totalEggProduction',
		'brokenEggCount',
		'nhe',
		'std',
		'percent',
		'avgEggCount',
		'lightStart',
		'lightOut',
		'lightLux',
		'lightTime',
		'feedMale',
		'feedFemale',
		'feedTypeId',
		'weightMale',
		'weightFemale',
		'mortalityMale',
		'mortalityFemale',
		'cullingMale',
		'cullingFemale',
		'male',
		'female',
		'description',
		'coolingPad1',
		'coolingPad2',
		'coolingPad3',
		'water',
		'fan',
		'feedingTrolly',
		'screeper',
		'conveyer',
	];


	public function getDailyEntriesForList($pageIndex, $pageSize, $shedId, $date, $lot)
	{
		$filterString = '';
		if (!empty($shedId) && empty($date) && empty($lot)) {
			$filterString = " WHERE me.shedId =" . $shedId;
		}
		if (empty($shedId) && empty($date) && !empty($lot)) {
			$filterString = " WHERE me.lot =" . $lot;
		}
		if (!empty($date) && empty($shedId) && empty($lot)) {
			$filterString = " WHERE me.date ='" . $date . "'";
		}
		if (!empty($date) && !empty($shedId) && empty($lot)) {
			$filterString = " WHERE me.shedId = " . $shedId . " AND me.date ='" . $date . "'";
		}
		if (empty($date) && !empty($shedId) && !empty($lot)) {
			$filterString = " WHERE me.shedId = " . $shedId . " AND me.lot =" . $lot;
		}
		if (!empty($date) && empty($shedId) && !empty($lot)) {
			$filterString = " WHERE me.lot = " . $lot . " AND me.date ='" . $date . "'";
		}
		if (!empty($date) && !empty($shedId) && !empty($lot)) {
			$filterString = " WHERE me.lot = " . $lot . " AND me.lot =" . $lot . " AND me.date ='" . $date . "'";
		}

		$queryDataString = "	
        SELECT 
        me.id as id,
		me.dateBs as dateBs,
        me.shedId as shedId,
        s.name as shedName,
        s.description as shedDetails,
        me.lot as lot,
		me.mortalityMale as mortalityMale,
		me.mortalityFemale as mortalityFemale,
		me.cullingMale as cullingMale,
		me.cullingFemale as cullingFemale,
        me.description as description
        FROM dailyentries me
        LEFT JOIN sheds s on me.shedId = s.id 
		" . $filterString . "
        ORDER BY me.date DESC
        LIMIT " . $pageSize . " OFFSET " . ($pageIndex - 1) * $pageSize;

		$queryCountString = "
        SELECT 
		CEILING(Count(*)/" . $pageSize . ") as count
        FROM dailyentries me " . $filterString;

		$dataQuery = $this->db->query($queryDataString);
		$countQuery = $this->db->query($queryCountString);
		// // your object result
		$data_object = $dataQuery->getResult();
		$count_object = $countQuery->getResult();

		$result = [
			'data'   => $data_object,
			'count'    => $count_object[0]->count,
			'pageIndex' => $pageIndex,
			'pageSize' => $pageSize
		];
		return $result;
	}

	public function getDailyEnteryDetailById($id)
	{
		$query = "SELECT me.id as id,
					me.dateBs as dateBs,
					me.shedId as shedId,
					s.name as shedName,
					bd.name as breedTypeName,
					fd.name as name,
					s.description as shedDetails,
					me.lot as lot,
					me.morningInTemp as morningInTemp,
					me.morningOutTemp as morninOutTemp,
					me.morningInHumidity as morningInHumidity,
					me.morningOutHumidity as morningOutHumidity,
					me.eveningInHumidity as eveningInHumidity,
					me.eveningOutHumidity as eveningOutHumidity,
					me.eveningInTemp as eveningInTemp,
					me.eveningOutTemp as eveningOutTemp, 
					me.lightTime as lightTime,
					me.lightOut as lightOut,
					me.lightLux as lightLux,
					me.male as male,
					me.female as female,
					me.feedMale as feedMale,
					me.feedFemale as feedFemale,
					me.weightMale as weightMale,
					me.weightFemale as weightFemale,
					me.mortalityMale as mortalityMale,
					me.mortalityFemale as mortalityFemale,
					me.cullingMale as cullingMale,
					me.cullingFemale as cullingFemale,
					me.totalEggProduction as totalEggProduction,
					me.brokenEggCount as brokenEggCount,
					me.avgEggWeight as avgEggWeight,
					me.nhe as nhe,
					me.std as std,
					me.coolingPad1 as coolingPad1,
					me.coolingPad2 as coolingPad2,
					me.coolingPad3 as coolingPad3,
					me.water as water,
					me.fan as fan,
					me.lightStart as lightStart,
					me.feedingTrolly as feedingTrolly,
					me.screeper as screeper,
					me.conveyer as conveyer,
					me.description as description
					FROM dailyentries me
					LEFT JOIN sheds s ON me.shedId = s.id
					LEFT JOIN feedtypes fd ON me.feedTypeId = fd.id
					LEFT JOIN stock st ON me.shedId = st.shedId AND me.lot = st.lot
					LEFT JOIN breeds bd ON bd.id= st.breedTypeId
					WHERE me.id = " . $id;

		$dataQuery = $this->db->query($query);
		$data_object = $dataQuery->getResult();
		$medicine = array();
		$data_object[0]->medicine = $medicine;
		// $data_object[0]->lightLux = round($data_object[0]->lightLux,2);



		return $data_object;
	}

	public function getLatestDailyEntry($shedId)
	{
		$query = "SELECT lot from dailyentries WHERE shedId = " .$shedId . " ORDER BY id DESC  LIMIT 1" ;
		$dataQuery = $this->db->query($query);
		$data_object = $dataQuery->getResult();
		return $data_object;
	}
}
