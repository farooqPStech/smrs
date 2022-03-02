<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignRouteController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
// Route::view('forgot_password', 'auth.reset_password')->name('password.reset');

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('password/expired', 'Auth\ExpiredPasswordController@expired')
        ->name('password.expired');
    Route::post('password/post_expired', 'Auth\ExpiredPasswordController@postExpired')
        ->name('password.post_expired');
    Route::middleware(['password_expired'])->group(function () {

        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/home/verify/{readingid}/{type}/{userid}', 'HomeController@verify')->name('verify');
        Route::get('/home/revoke/{readingid}/{type}/{userid}', 'HomeController@revoke')->name('revoke');
        Route::get('/home/reject/{readingid}/{type}/{userid}/{reason}/{rejectcode}', 'HomeController@reject')->name('reject');
        Route::get('/home/viewimage/{id}', 'HomeController@viewImage')->name('viewImage');
        Route::get('/home/image/{id}', 'HomeController@image')->name('image');
        Route::get('/home/viewtrend/{id}', 'HomeController@viewTrend')->name('viewTrend');
        Route::get('/home/uploadtojde', 'HomeController@uploadToJDE')->name('uploadToJDE');
        Route::get('/home/alert', 'HomeController@alert')->name('alert');
        Route::post('/home/updateapproval', 'HomeController@updateApproval')->name('updateApproval');

        //route assignment from branch admin 9
			Route::get('route_assign', [AssignRouteController::class, 'index'])->name('route_assign');
			Route::get('route_assign/{id}', [AssignRouteController::class, 'delete']);
			Route::get('route_assign/{id}/edit', [AssignRouteController::class, 'edit'])->name('routeAssign');
			Route::post('assignRouteUpdate', [AssignRouteController::class, 'update'])->name('assignRouteUpdate');

		//route
        Route::get('/route/list', 'RouteController@list')->name('list');
		Route::get('/route/listassigned', 'RouteController@listAssigned')->name('listAssigned');
		Route::get('/route/assignroute', 'RouteController@assignRoute')->name('assignRoute');
		Route::get('/route/assignrouteprev', 'RouteController@assignRoutePrev')->name('assignRoutePrev');
		Route::get('/route/routeassignment', 'RouteController@routeAssignment')->name('routeAssignment');
		Route::get('/route/adjustroute', 'RouteController@adjustRoute')->name('adjustRoute');
		Route::get('/route/newassignroute/{routes}/{ids}', 'RouteController@newAssignRoute')->name('newAssignRoute');
		Route::get('/route/unassign/{ids}', 'RouteController@unassign')->name('unassign');
		Route::get('/route/routeassignmentnewassign/{routes}/{ids}', 'RouteController@routeAssignmentNewAssign')->name('routeAssignmentNewAssign');
		Route::get('/route/routeassignmentunassign/{ids}', 'RouteController@routeAssignmentUnassign')->name('routeAssignmentUnassign');

		Route::get('/route/combineroutetable', 'RouteController@combineRouteTable')->name('combineRouteTable');
		Route::get('/route/assigncombineroute/{routes}', 'RouteController@assignCombineRoute')->name('assignCombineRoute');
		Route::get('/route/unassigncombineroute/{routes}', 'RouteController@unassignCombineRoute')->name('unassignCombineRoute');
		Route::get('/route/routeidtable', 'RouteController@routeIDTable')->name('routeIDTable');
		Route::get('/route/splitroutetable', 'RouteController@splitRouteTable')->name('splitRouteTable');
		Route::get('/route/changehandheldtable', 'RouteController@changeHandheldTable')->name('changeHandheldTable');
		Route::get('/route/changehandheld/{route}/{handheld}', 'RouteController@changeHandheld')->name('changeHandheld');
		Route::get('/route/getroute/{id}', 'RouteController@getRoute')->name('getRoute');
		Route::get('/route/getsubroute/{id}', 'RouteController@getSubRoute')->name('getSubRoute');
		Route::get('/route/updatesubroute/{id}/{startSeq}/{endSeq}', 'RouteController@updateSubRoute')->name('updateSubRoute');
		Route::get('/route/updatesubrouteselected/{array}/{id}', 'RouteController@updateSubRouteSelected')->name('updateSubRouteSelected');
		Route::get('/route/updatesplitrouteselected/{array}', 'RouteController@updateSplitRouteSelected')->name('updateSplitRouteSelected');

		Route::post('/route/assignsingleroute/', 'RouteController@assignSingleRoute')->name('assignSingleRoute');
		Route::get('/route/unassignSingleRoute/{id}', 'RouteController@unassignSingleRoute')->name('unassignSingleRoute');


		//handheld
		Route::get('/handheld/list', 'HandheldController@list')->name('list');
		Route::get('/handheld/addhandheld', 'HandheldController@addHandheld')->name('addHandheld');
		Route::post('/handheld/addnewhandheld', 'HandheldController@addNewHandheld')->name('addNewHandheld');
		Route::get('/handheld/edithandheld/{id}', 'HandheldController@editHandheld')->name('editHandheld');
		Route::post('/handheld/updatehandheld', 'HandheldController@updateHandheld')->name('updateHandheld');
		Route::get('/handheld/removehandheld/{id}', 'HandheldController@removeHandheld')->name('removeHandheld');

		//printer
		Route::get('/printer/list', 'PrinterController@list')->name('list');
		Route::get('/printer/addprinter', 'PrinterController@addPrinter')->name('addPrinter');
		Route::post('/printer/addnewprinter', 'PrinterController@addNewPrinter')->name('addNewPrinter');
		Route::get('/printer/editprinter/{id}', 'PrinterController@editPrinter')->name('editPrinter');
		Route::post('/printer/updateprinter', 'PrinterController@updatePrinter')->name('updatePrinter');
		Route::get('/printer/removeprinter/{id}', 'PrinterController@removePrinter')->name('removePrinter');

		//meter
		Route::get('/meter/list', 'MeterController@list')->name('meterlist');
		Route::get('/meter/viewtrend/{id}', 'MeterController@viewTrend')->name('viewTrend');

		//user
		Route::get('/user/list', 'UserController@list')->name('list');
		Route::get('/user/edit/{id}', 'UserController@edit')->name('edit');
		Route::get('/user/resetpassword/{id}', 'UserController@resetPassword')->name('resetPassword');
		Route::post('/user/update', 'UserController@update')->name('update');
		Route::get('/user/adduser', 'UserController@addUser')->name('addUser');
		Route::post('/user/addnewuser', 'UserController@addNewUser')->name('addNewUser');
		Route::get('/user/removeuser/{id}', 'UserController@removeUser')->name('removeUser');
		Route::get('/user/changepassword', 'UserController@changePassword')->name('changePassword');
		Route::post('/user/updatechangepassword', 'UserController@updateChangePassword')->name('updateChangePassword');
		// Route::get('/user/email', 'UserController@email')->name('email');


		Route::get('/report/list', 'ReportController@list')->name('list');

		Route::get('/log/list', 'LogController@list')->name('list');
		Route::get('/log/web', 'LogController@web')->name('web');
		Route::get('/log/handheld', 'LogController@handheld')->name('handheld');

		Route::get('/consumer/list', 'ConsumerController@list')->name('list');
		Route::get('/consumer/industriallist', 'ConsumerController@industrialList')->name('industrialList');
		Route::get('/consumer/residentlist', 'ConsumerController@residentList')->name('residentList');
		Route::get('/consumer/view/{id}', 'ConsumerController@view')->name('view');


		Route::get('/setting/info', 'SettingController@info')->name('info');
		Route::post('/setting/infoupdate', 'SettingController@infoUpdate')->name('infoUpdate');

		Route::get('/broadcast/list', 'BroadcastController@list')->name('list');
		Route::get('/broadcast/addbroadcast', 'BroadcastController@addBroadcast')->name('addBroadcast');
		Route::post('/broadcast/addnewbroadcast', 'BroadcastController@addNewBroadcast')->name('addNewBroadcast');
		Route::post('/broadcast/updatebroadcast', 'BroadcastController@updateBroadcast')->name('updateBroadcast');
		Route::get('/broadcast/sendbroadcast/{id}', 'BroadcastController@sendBroadcast')->name('sendBroadcast');
		Route::get('/broadcast/edit/{id}', 'BroadcastController@edit')->name('edit');
		Route::get('/broadcast/remove/{id}', 'BroadcastController@remove')->name('remove');
		//setting
		Route::get('/setting/branch', 'SettingController@branch')->name('branch');
		Route::get('/setting/addbranch', 'SettingController@addBranch')->name('addBranch');
		Route::post('/setting/addnewbranch', 'SettingController@addNewBranch')->name('addNewBranch');
		Route::get('/setting/editbranch/{id}', 'SettingController@editBranch')->name('editBranch');
		Route::post('/setting/updatebranch', 'SettingController@updateBranch')->name('updateBranch');
		Route::get('/setting/removebranch/{id}', 'SettingController@removeBranch')->name('removeBranch');

		Route::get('/setting/issuecode', 'SettingController@issueCode')->name('issueCode');
		Route::get('/setting/addissue', 'SettingController@addIssue')->name('addIssue');
		Route::post('/setting/addnewissue', 'SettingController@addNewIssue')->name('addNewIssue');
		Route::get('/setting/editissue/{id}', 'SettingController@editIssue')->name('editIssue');
		Route::post('/setting/updateissue', 'SettingController@updateIssue')->name('updateIssue');
		Route::get('/setting/removeissue/{id}', 'SettingController@removeIssue')->name('removeIssue');

		Route::get('/setting/obstaclecode', 'SettingController@obstacleCode')->name('obstacleCode');
		Route::get('/setting/addobstacle', 'SettingController@addObstacle')->name('addObstacle');
		Route::post('/setting/addnewobstacle', 'SettingController@addNewObstacle')->name('addNewObstacle');
		Route::get('/setting/editobstacle/{id}', 'SettingController@editObstacle')->name('editObstacle');
		Route::post('/setting/updateobstacle', 'SettingController@updateObstacle')->name('updateObstacle');
		Route::get('/setting/removeobstacle/{id}', 'SettingController@removeObstacle')->name('removeObstacle');

		Route::get('/setting/rejectcode', 'SettingController@rejectCode')->name('rejectCode');
		Route::get('/setting/addreject', 'SettingController@addReject')->name('addReject');
		Route::post('/setting/addnewreject', 'SettingController@addNewReject')->name('addNewReject');
		Route::get('/setting/editreject/{id}', 'SettingController@editReject')->name('editReject');
		Route::post('/setting/updatereject', 'SettingController@updateReject')->name('updateReject');
		Route::get('/setting/removereject/{id}', 'SettingController@removeReject')->name('removeReject');

		Route::get('/setting/meterlocation', 'SettingController@meterLocation')->name('meterLocation');
		Route::get('/setting/addmeterlocation', 'SettingController@addMeterLocation')->name('addMeterLocation');
		Route::post('/setting/addnewmeterlocation', 'SettingController@addNewMeterLocation')->name('addNewMeterLocation');
		Route::get('/setting/editmeterlocation/{id}', 'SettingController@editMeterLocation')->name('editMeterLocation');
		Route::post('/setting/updatemeterlocation', 'SettingController@updateMeterLocation')->name('updateMeterLocation');
		Route::get('/setting/removemeterlocation/{id}', 'SettingController@removeMeterLocation')->name('removeMeterLocation');

		Route::get('/setting/highlow', 'SettingController@highlow')->name('highlow');
		Route::get('/setting/addhighlow', 'SettingController@addHighlow')->name('addHighlow');
		Route::post('/setting/addnewhighlow', 'SettingController@addNewHighlow')->name('addNewHighlow');
		Route::get('/setting/edithighlow/{id}', 'SettingController@editHighlow')->name('editHighlow');
		Route::post('/setting/updatehighlow', 'SettingController@updateHighlow')->name('updateHighlow');
		Route::get('/setting/removehighlow/{id}', 'SettingController@removeHighlow')->name('removeHighlow');

		Route::get('/setting/price', 'SettingController@price')->name('price');
		Route::get('/setting/addprice', 'SettingController@addPrice')->name('addHighlow');
		Route::post('/setting/addnewprice', 'SettingController@addNewPrice')->name('addNewPrice');
		Route::get('/setting/editprice/{id}', 'SettingController@editPrice')->name('editPrice');
		Route::post('/setting/updateprice', 'SettingController@updatePrice')->name('updatePrice');
		Route::get('/setting/removeprice/{id}', 'SettingController@removePrice')->name('removePrice');

		Route::get('/setting/billing', 'SettingController@billing')->name('billing');
		Route::get('/setting/addbilling', 'SettingController@addBilling')->name('addBilling');
		Route::post('/setting/addnewbilling', 'SettingController@addNewBilling')->name('addNewBilling');
		Route::get('/setting/editbilling/{id}', 'SettingController@editBilling')->name('editBilling');
		Route::post('/setting/updatebilling', 'SettingController@updateBilling')->name('updateBilling');
		Route::get('/setting/removebilling/{id}', 'SettingController@removeBilling')->name('removeBilling');

		Route::get('/setting/gcpt', 'SettingController@gcpt')->name('gcpt');
		Route::get('/setting/addgcpt', 'SettingController@addGCPT')->name('addGCPT');
		Route::post('/setting/addnewgcpt', 'SettingController@addNewGCPT')->name('addNewGCPT');
		Route::get('/setting/editgcpt/{id}', 'SettingController@editGCPT')->name('editGCPT');
		Route::post('/setting/updategcpt', 'SettingController@updateGCPT')->name('updateGCPT');
		Route::get('/setting/removegcpt/{id}', 'SettingController@removeGCPT')->name('removeGCPT');

		Route::get('/setting/appversion', 'SettingController@appVersion')->name('appVersion');
		Route::get('/setting/addapp', 'SettingController@addApp')->name('addApp');
		Route::post('/setting/addnewapp', 'SettingController@addNewApp')->name('addNewApp');
		Route::get('/setting/editapp/{id}', 'SettingController@editApp')->name('editApp');
		Route::post('/setting/updateapp', 'SettingController@updateApp')->name('updateApp');
		Route::get('/setting/setactive/{id}', 'SettingController@setActive')->name('setActive');

		Route::get('/setting/duedate', 'SettingController@dueDate')->name('dueDate');
		Route::post('/setting/updateduedate', 'SettingController@updateDueDate')->name('updateDueDate');

		Route::get('/setting/billnote', 'SettingController@billNote')->name('billNote');
		Route::get('/setting/addbillnote', 'SettingController@addBillNote')->name('addBillNote');
		Route::post('/setting/addnewbillnote', 'SettingController@addNewBillNote')->name('addNewBillNote');
		Route::get('/setting/editbillnote/{id}', 'SettingController@editBillNote')->name('editBillNote');
		Route::post('/setting/updatebillnote', 'SettingController@updateBillNote')->name('updateBillNote');
		Route::get('/setting/setactivebillnote/{id}', 'SettingController@setActiveBillNote')->name('setActiveBillNote');

		Route::get('/setting/file', 'SettingController@file')->name('file');
		Route::post('/setting/updatefile', 'SettingController@updateFile')->name('updateFile');

		Route::get('/setting/printables', 'SettingController@printables')->name('printables');
		Route::post('/setting/updateprintables', 'SettingController@updatePrintables')->name('updatePrintables');

		Route::get('/setting/consumptionvariant', 'SettingController@consumptionVariant')->name('consumptionVariant');
		Route::post('/setting/updatecv', 'SettingController@updatecv')->name('updatecv');

		Route::get('/report/billingdetailsbyroute', 'ReportController@billingDetailsByRoute')->name('billingDetailsByRoute');
		Route::get('/report/billingdetailssummarybyroute', 'ReportController@billingDetailsSummaryByRoute')->name('billingDetailsSummaryByRoute');
		Route::get('/report/cancelbilllisting', 'ReportController@cancelBillListing')->name('cancelBillListing');
		Route::get('/report/billingdetailsbyroutegmsb', 'ReportController@billingDetailsByRouteGMSB')->name('billingDetailsByRouteGMSB');
		Route::get('/report/kivbilllisting', 'ReportController@KIVBillListing')->name('KIVBillListing');
		Route::get('/report/loginlogout', 'ReportController@loginLogout')->name('loginLogout');
		Route::get('/report/meterrangecheck', 'ReportController@meterRangeCheck')->name('meterRangeCheck');
		Route::get('/report/meterrangechecksummary', 'ReportController@meterRangeCheckSummary')->name('meterRangeCheckSummary');
		Route::get('/report/dailysalesperformancebyroute', 'ReportController@dailySalesPerformanceByRoute')->name('dailySalesPerformanceByRoute');
		Route::get('/report/dailysalesperformancebytariff', 'ReportController@dailySalesPerformanceByTariff')->name('dailySalesPerformanceByTariff');
		Route::get('/report/readinginterval', 'ReportController@readingInterval')->name('readingInterval');
		Route::get('/report/readingintervalsummary', 'ReportController@readingIntervalSummary')->name('readingIntervalSummary');

		Route::get('/report/billingdetailsbyroutetable', 'ReportController@billingDetailsByRouteTable')->name('billingDetailsByRouteTable');
		Route::get('/report/billingdetailssummarybyroutetable', 'ReportController@billingDetailsSummaryByRouteTable')->name('billingDetailsSummaryByRouteTable');
		Route::get('/report/cancelbilllistingtable', 'ReportController@cancelBillListingTable')->name('cancelBillListingTable');
		Route::get('/report/billingdetailsbyroutegmsbtable', 'ReportController@billingDetailsByRouteGMSBTable')->name('billingDetailsByRouteGMSBTable');
		Route::get('/report/kivbilllistingtable', 'ReportController@KIVBillListingTable')->name('KIVBillListingTable');
		Route::get('/report/loginlogouttable', 'ReportController@loginLogoutTable')->name('loginLogoutTable');
		Route::get('/report/meterrangechecktable', 'ReportController@meterRangeCheckTable')->name('meterRangeCheckTable');
		Route::get('/report/meterrangechecksummarytable', 'ReportController@meterRangeCheckSummaryTable')->name('meterRangeCheckSummaryTable');
		Route::get('/report/dailysalesperformancebyroutetable', 'ReportController@dailySalesPerformanceByRouteTable')->name('dailySalesPerformanceByRouteTable');
		Route::get('/report/dailysalesperformancebytarifftable', 'ReportController@dailySalesPerformanceByTariffTable')->name('dailySalesPerformanceByTariffTable');
		Route::get('/report/readingintervaltable', 'ReportController@readingIntervalTable')->name('readingIntervalTable');
		Route::get('/report/readingintervalsummarytable', 'ReportController@readingIntervalSummaryTable')->name('readingIntervalSummaryTable');

		Route::get('/report/loginlogoutresident', 'ReportController@loginLogoutResident')->name('loginLogoutResident');
		Route::get('/report/billingdetailsbyamountresident', 'ReportController@billingDetailsByAmountResident')->name('billingDetailsByAmountResident');
		Route::get('/report/billingdetailsbyrouteresident', 'ReportController@billingDetailsByRouteResident')->name('billingDetailsByRouteResident');
		Route::get('/report/billingdetailssummarybyrouteresident', 'ReportController@billingDetailsSummaryByRouteResident')->name('billingDetailsSummaryByRouteResident');
		Route::get('/report/billingdetailsbyroutegmsbresident', 'ReportController@billingDetailsByRouteGMSBResident')->name('billingDetailsByRouteGMSBResident');
				Route::get('/report/loginlogoutresidenttable', 'ReportController@loginLogoutResidentTable')->name('loginLogoutResidentTable');
		Route::get('/report/billingdetailsbyamountresidenttable', 'ReportController@billingDetailsByAmountResidentTable')->name('billingDetailsByAmountResidentTable');
		Route::get('/report/billingdetailsbyrouteresidenttable', 'ReportController@billingDetailsByRouteResidentTable')->name('billingDetailsByRouteResidentTable');
		Route::get('/report/billingdetailssummarybyrouteresidenttable', 'ReportController@billingDetailsSummaryByRouteResidentTable')->name('billingDetailsSummaryByRouteResidentTable');
		Route::get('/report/billingdetailsbyroutegmsbresidenttable', 'ReportController@billingDetailsByRouteGMSBResidentTable')->name('billingDetailsByRouteGMSBResidentTable');


		//temporary
		Route::post('/home/reader', 'HomeController@reader')->name('reader');

    });

});





