<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('login', 'Auth::login');
$routes->get('/', 'Home::index', ['filter' => 'authGuard']);
$routes->get('summary-report/(:any)', 'Home::getGroupDetail/$1', ['filter' => 'authGuard']); /*  this is for dashboard */
$routes->get('summary-report-detail/(:any)', 'Home::getShedData/$1', ['filter' => 'authGuard']); /*  this is for dashboard */
$routes->get('api/summary-report-detail', 'Api\Report::getData', ['filter' => 'authGuard']); /*  this is for dashboard */
$routes->get('user-log', 'UserLogController::index', ['filter' => 'authGuard']); /*  this is for user log */
$routes->group('api/settings', function($routes){

	// shed settings
	$routes->get('shed', 'Api\Setting::getAllSheds', ['filter' => 'authGuard']);
	$routes->post('shed', 'Api\Setting::addOrUpdateShed', ['filter' => 'authGuard']);
	$routes->delete('shed', 'Api\Setting::deleteShed', ['filter' => 'authGuard']);
	// unit settings
	$routes->get('unit', 'Api\Setting::getAllUnits', ['filter' => 'authGuard']);
	$routes->post('unit', 'Api\Setting::addOrUpdateUnit', ['filter' => 'authGuard']);
	$routes->delete('unit', 'Api\Setting::deleteUnit', ['filter' => 'authGuard']);
	// remarks type settings
	$routes->get('remarks', 'Api\Setting::getAllRemarks', ['filter' => 'authGuard']);
	$routes->get('remarks/active', 'Api\Setting::getAllActiveRemarks', ['filter' => 'authGuard']);
	$routes->post('remarks', 'Api\Setting::addOrUpdateRemarks', ['filter' => 'authGuard']);
	$routes->delete('remarks', 'Api\Setting::deleteRemarks', ['filter' => 'authGuard']);
	// group settings
	$routes->get('group', 'Api\Setting::getAllGroups', ['filter' => 'authGuard']);
	$routes->post('group', 'Api\Setting::addOrUpdateGroup', ['filter' => 'authGuard']);
	$routes->delete('group', 'Api\Setting::deleteGroup', ['filter' => 'authGuard']);
	// feedType settings
	$routes->get('feedType', 'Api\Setting::getAllFeedTypes', ['filter' => 'authGuard']);
	$routes->post('feedType', 'Api\Setting::addOrUpdateFeedType', ['filter' => 'authGuard']);
	$routes->delete('feedType', 'Api\Setting::deleteFeedType', ['filter' => 'authGuard']);
	// poultryType settings
	$routes->get('poultryType', 'Api\Setting::getAllPoultryTypes', ['filter' => 'authGuard']);
	$routes->post('poultryType', 'Api\Setting::addOrUpdatePoultryType', ['filter' => 'authGuard']);
	$routes->delete('poultryType', 'Api\Setting::deletePoultryType', ['filter' => 'authGuard']);
	// breed settings
	$routes->get('breed', 'Api\Setting::getAllBreeds', ['filter' => 'authGuard']);
	$routes->post('breed', 'Api\Setting::addOrUpdateBreed', ['filter' => 'authGuard']);
	$routes->delete('breed', 'Api\Setting::deleteBreed', ['filter' => 'authGuard']);
	// medicineVaccine settings
	$routes->get('medicineVaccine', 'Api\Setting::getAllMedicineVaccines', ['filter' => 'authGuard']);
	$routes->post('medicineVaccine', 'Api\Setting::addOrUpdateMedicineVaccine', ['filter' => 'authGuard']);
	$routes->delete('medicineVaccine', 'Api\Setting::deleteMedicineVaccine', ['filter' => 'authGuard']);
	// standardBreederInformation settings
	$routes->get('standardBreederInformation', 'Api\Setting::getAllStandartBreederInformations', ['filter' => 'authGuard']);
	$routes->post('standardBreederInformation', 'Api\Setting::addOrUpdateStandartBreederInformation', ['filter' => 'authGuard']);
	$routes->delete('standardBreederInformation', 'Api\Setting::deleteStandartBreederInformation', ['filter' => 'authGuard']);
	// standardBreederPerformances settings
	$routes->get('standardBreederPerformances', 'Api\Setting::getAllStandardBreederPerformances', ['filter' => 'authGuard']);
	$routes->post('standardBreederPerformances', 'Api\Setting::addOrUpdateStandardBreederPerformance', ['filter' => 'authGuard']);
	$routes->delete('standardBreederPerformances', 'Api\Setting::deleteStandardBreederPerformance', ['filter' => 'authGuard']);
	// standardHatcheryInformation settings
	$routes->get('standardHatcheryInformation', 'Api\Setting::getAllStandardHatcheryInformations', ['filter' => 'authGuard']);
	$routes->post('standardHatcheryInformation', 'Api\Setting::addOrUpdateStandardHatcheryInformation', ['filter' => 'authGuard']);
	$routes->delete('standardHatcheryInformation', 'Api\Setting::deleteStandardHatcheryInformation', ['filter' => 'authGuard']);
	//
});
$routes->group('api', function($routes){
	$routes->get('mainEntry', 'Api\Entry::getAllMainEntries', ['filter' => 'authGuard']);
	$routes->post('mainEntry', 'Api\Entry::addUpdateMainEntries', ['filter' => 'authGuard']);
	$routes->get('mainEntry/shed', 'Api\Entry::getShedDataFromEntries', ['filter' => 'authGuard']);
	$routes->get('dailyEntry', 'Api\Entry::getAllDailyData', ['filter' => 'authGuard']);
	$routes->get('dailyEntry/detail', 'Api\Entry::getDailyEntryDetail', ['filter' => 'authGuard']);/*   */
	$routes->get('addMoreEntry', 'Api\Entry::getEntryData', ['filter' => 'authGuard']);/*   */
	$routes->get('mainEntry/resAge', 'Api\Entry::getArrivalAge', ['filter' => 'authGuard']); /*    */
	$routes->get('dailyEntry/shedAndDate', 'Api\Entry::getDailyShedData', ['filter' => 'authGuard']); 
	$routes->post('dailyEntry/add', 'Api\Entry::addUpdateDailyEntry', ['filter' => 'authGuard']);
});
$routes->group('api', function($routes){
	$routes->get('stock', 'Api\Appstock::getAllStocks', ['filter' => 'authGuard']);
	$routes->get('transfer/update/', 'Api\Appstock::getAllStocks', ['filter' => 'authGuard']);
	$routes->get('stock/shed', 'Api\Appstock::getByShedId', ['filter' => 'authGuard']);
	$routes->get('stock/transfer', 'Api\Transfer::getAllTransfers', ['filter' => 'authGuard']);
	$routes->get('stock/transfer/transferDateAndFromShed', 'Api\Transfer::getTransfersByShedAndDate', ['filter' => 'authGuard']);
	$routes->post('stock/transfer/add', 'Api\Transfer::addUpdateTransfer', ['filter' => 'authGuard']);
	$routes->post('stock/transfer/addT', 'Api\Transfer::submitTransfer', ['filter' => 'authGuard']);
	$routes->post('transfer/update', 'Api\Transfer::updateTransfer', ['filter' => 'authGuard']);
});
$routes->group('api', function($routes){
	// dashboard
	$routes->get('group', 'Api\Report::getAllGroup', ['filter' => 'authGuard']);
	$routes->get('farmReport', 'Api\Report::getFarmReport', ['filter' => 'authGuard']);
	
});
$routes->group('api/excel', function($routes){
	$routes->post('daily', 'Api\Excel::saveDailyData', ['filter' => 'authGuard']);
	
});


$routes->group('settings', function($routes){
	$routes->get('shed', 'Setting::shed', ['filter' => 'authGuard']);
	$routes->get('group', 'Setting::group', ['filter' => 'authGuard']);
	$routes->get('feedType', 'Setting::feedType', ['filter' => 'authGuard']);
	$routes->get('poultryType', 'Setting::poultryType', ['filter' => 'authGuard']);
	$routes->get('breed', 'Setting::breed', ['filter' => 'authGuard']);
	$routes->get('unit', 'Setting::unit', ['filter' => 'authGuard']);
	$routes->get('remarksType', 'Setting::remarks', ['filter' => 'authGuard']);
	$routes->get('medicineVaccine', 'Setting::medicineVaccine', ['filter' => 'authGuard']);
	$routes->get('standardBreederInformation', 'Setting::standardBreederInformation', ['filter' => 'authGuard']);
	$routes->get('standardBreederPerformances', 'Setting::standardBreederPerformances', ['filter' => 'authGuard']);
	$routes->get('standardHatcheryInformation', 'Setting::standardHatcheryInformation', ['filter' => 'authGuard']);
});
$routes->group('', function($routes){
	$routes->get('mainEntry', 'Entry::mainEntry', ['filter' => 'authGuard']);
	$routes->get('mainEntry/add', 'Entry::mainEntryAdd', ['filter' => 'authGuard']);
	$routes->get('mainEntry/update/(:num)', 'Entry::mainEntryUpdate/$1', ['filter' => 'authGuard']);
	$routes->get('dailyEntry', 'Entry::dailyEntry', ['filter' => 'authGuard']);
	$routes->get('dailyEntry/detail/(:num)', 'Entry::dailyEntryDetail/$1', ['filter' => 'authGuard']);
	$routes->get('dailyEntry/add', 'Entry::dailyEntryAdd', ['filter' => 'authGuard']);
});
$routes->group('stock', function($routes){
	$routes->get('', 'Appstock::totalStock', ['filter' => 'authGuard']);
	
});
$routes->group('transfer', function($routes){
	$routes->get('', 'Transfer::transfer', ['filter' => 'authGuard']);
	$routes->get('add', 'Transfer::transferAdd', ['filter' => 'authGuard']);
	$routes->get('update/(:num)', 'Transfer::transferUpdate/$1', ['filter' => 'authGuard']);
});
$routes->group('farm-report', function($routes){
	$routes->get('', 'Report::farmReport', ['filter' => 'authGuard']);
});
$routes->group('excel', function($routes){
	$routes->get('daily', 'Excel::dailyEntry', ['filter' => 'authGuard']);
});


$routes->post('loginPost', 'Auth::loginPost');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register', ['filter' => 'authGuard']);
$routes->post('registerPost', 'Auth::registerPost', ['filter' => 'authGuard']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
