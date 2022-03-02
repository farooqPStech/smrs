<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\JDEDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('auth',[ApiController::class, 'auth']);
Route::post('downloadsetting',[ApiController::class, 'downloadSetting']);
Route::post('filedownloadindustrial',[ApiController::class, 'fileDownloadIndustrial']);
Route::post('fileuploadindustrial',[ApiController::class, 'fileUploadIndustrial']);
Route::post('filedownloadresidence',[ApiController::class, 'fileDownloadResidence']);
Route::post('filedownloadtariff',[ApiController::class, 'fileDownloadTariff']);
Route::post('savereading',[ApiController::class, 'saveReading']);
Route::post('syncsingle',[ApiController::class, 'syncSingle']);
Route::post('updatereadingsingle',[ApiController::class, 'updateReadingSingle']);
Route::post('checkrejectedreading',[ApiController::class, 'checkRejectedReading']);
Route::post('broadcastall',[ApiController::class, 'broadcastAll']);
Route::post('broadcastlatest',[ApiController::class, 'broadcastLatest']);
Route::post('resetpassword', [ApiController::class, 'resetPassword']);
Route::post('saveplayerid',[ApiController::class, 'savePlayerId']);
Route::post('senddata',[ApiController::class, 'sendData']);
Route::post('getnewassignedroute',[ApiController::class, 'getNewAssignedRoute']);
Route::post('getupdatedmeterinfo',[ApiController::class, 'getUpdatedMeterInfo']);
Route::post('savebill',[ApiController::class, 'saveBill']);
Route::post('updatebill',[ApiController::class, 'updateBill']);
Route::post('syncbillsingle',[ApiController::class, 'syncBillSingle']);
Route::post('syncbill',[ApiController::class, 'syncBill']);
Route::post('cancelbill',[ApiController::class, 'cancelBill']);
Route::get('getdownload',[ApiController::class, 'getDownload']);
Route::post('logout',[ApiController::class, 'logout']);
Route::post('noti',[ApiController::class, 'noti']);
Route::post('filedownloadindustrialAPI',[ApiController::class, 'fileDownloadIndustrialAPI']);

//add for cancel reading
Route::post('cancel-reading', [ApiController::class, 'cancelReading']);

//jde load historical data
Route::post('jde-load-data', [JDEDataController::class, 'loadHistoricalData']);
