<?php

namespace App\Http\Controllers\Api;

// use Illuminate\Support\Str;
use App\Models\AppVersion;
use App\Models\Bill;
use App\Models\BillNote;
use App\Models\Broadcast;
use App\Models\CompanyInformations;
use App\Models\Consumer;
use App\Models\ConsumptionVariant;
use App\Models\DueDate;
use App\Models\Handheld;
use App\Models\HandheldRoute;
use App\Models\Highlow;
use App\Models\IssueCode;
use App\Models\Log;
use App\Models\Meter;
use App\Models\MeterLocation;
use App\Models\Notification;
use App\Models\ObstacleCode;
use App\Models\Price;
use App\Models\Printable;
use App\Models\Printer;
use App\Models\Reading;
use App\Models\Route;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Response;

class ApiController
{
    public function Auth(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required',
            'remember_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $data = User::where('username', $request->username)->where('type', 4)->first();

        if ($data) {

            $match = Hash::check($request->password, $data->password);

            if ($match) {
                $data->remember_token = $request->remember_token;
                $data->uuid = $request->remember_token;
                $data->save();

                $check = AppVersion::where('status', 1)->orderBy('created_at', 'desc')->first();

                $handheld = Handheld::where('uuid', $request->remember_token)->first();

                if ($handheld) {
                    $date_check = date('Y-m');
                    if (date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                    $handheldRoute = HandheldRoute::select('route_id')
                        ->distinct('route_id')->where('handheld_id', $handheld->id)
                    //->where('created_at', 'like', '%' . $date_check . '%')
                        ->pluck('route_id');

                    //dd($handheldRoute);
                    $route = Route::whereIn('id', $handheldRoute)->get();
                    $consumer = Consumer::whereIn('route_id', $handheldRoute)->get();
                    // $issueCode = IssueCode::all();
                    // $obstacleCode = ObstacleCode::all();
                    // $meterLocation = MeterLocation::all();

                    $subRoute = array();
                    for ($i = 0; $i < count($route); $i++) {
                        if ($route[$i]->combine != null || $route[$i]->combine != '') {

                            $ids = explode(',', $route[$i]->combine);
                            $consumerIds = Consumer::whereIn('route_id', $ids)->get();
                            $consumer = $consumer->merge($consumerIds);

                            for ($j = 0; $j < count($ids); $j++) {

                                $combineRoute = Route::find($ids[$j]);

                                $subRoute[$j] = $combineRoute;
                            }
                        }
                    }

                    // dd(count($consumer));

                    // for ($i=0; $i < count($subRoute); $i++) {

                    //      $routes = explode(',', $subRoute[$i]->combine);

                    //     for ($i=0; $i < count($routes); $i++) {

                    //         $consumerIds = Consumer::Where('route_id',$routes)->get();
                    //         $consumer->push($consumerIds);
                    //     }
                    // }

                    foreach ($consumer as $c) {
                        $meter = Meter::where('consumer_id', $c->id)->get();

                        for ($i = 0; $i < count($meter); $i++) {
                            $date_check = date('Y-m');
                            if (date('d') < 22) {
                                $date_check = date('Y-m', strtotime('-28 days'));
                            } else {
                                $date_check = date('Y-m');
                            }
                            $reading = Reading::Where('meter_id', $meter[$i]->id)->Where('created_at', 'like', '%' . $date_check . '%')->first();
                            $meter[$i]->reading = $reading;
                        }

                        $c->meter = $meter;

                        $date_check_2 = date('Y-m', strtotime('-28 days'));
                        if (date('d') < 22) {
                            $date_check_2 = date('Y-m', strtotime('-56 days'));
                        } else {
                            $date_check_2 = date('Y-m', strtotime('-28 days'));
                        }
                        $adjustment_bill = Bill::Where('consumer_id', $c->id)->Where('created_at', 'like', '%' . $date_check_2 . '%')->first();
                        $c->adjustment_bill = $adjustment_bill;
                    }

                    if ($check) {
                        if ($check->version_number == $request->version_number) {
                            //log
                            $new = new Log();
                            $new->user = $data->id;
                            $new->activity = "User Login At Handheld ID: " . $handheld->id;
                            $new->month = Date('m');
                            $new->year = Date('Y');
                            $new->save();

                            // $tariff = Price::max('effective_date')->orderBy('effective_date','desc')->first();
                            // $highlow = Highlow::select('*')->first();
                            // $dueDate = DueDate::select('*')->first();
                            // $billNote = BillNote::select('*')->get();
                            // $printer = Printer::where('branch_id',$data->branch_id)->get();
                            // $address = CompanyInformations::select('*')->get();

                            // $tariff = Price::where('effective_date', Price::max('effective_date'))->orderBy('effective_date','desc')->first();

                            // $tariff = Price::select('*')->get();
                            // $highlow = Highlow::select('*')->get();
                            // $dueDate = DueDate::select('*')->first();
                            // $billNote = BillNote::select('*')->where('status',1)->first();
                            // $printer = Printer::where('branch_id',$data->branch_id)->get();
                            // $address = CompanyInformations::select('*')->get();
                            $date_check = date('Y-m');
                            if (date('d') < 22) {
                                $date_check = date('Y-m', strtotime('-28 days'));
                            } else {
                                $date_check = date('Y-m');
                            }

                            $code = 'N';

                            $consumerIdsForCurrentRoute = Consumer::whereIn('route_id', $handheldRoute)->pluck('id');
                            $bill = Bill::whereIn('consumer_id', $consumerIdsForCurrentRoute)->where('created_at', 'like', '%' . $date_check . '%')
                            // ->leftjoin('consumers', 'bills.consumer_id', '=', 'consumers.id')
                            // ->leftjoin('handheld_routes', 'consumers.route_id', '=', 'handheld_routes.route_id')
                            // ->where('bills.bill_code','!=',$code)
                                ->get();

                            // foreach ($bill as $b) {
                            //     $date_check_2 = date('Y-m', strtotime('-28 days'));
                            //        if(date('d') < 22) {
                            //            $date_check_2 = date('Y-m', strtotime('-56 days'));
                            //        } else {
                            //            $date_check_2 = date('Y-m', strtotime('-28 days'));
                            //        }
                            //        $adjustment_bill = Bill::Where('consumer_id',$b->consumer_id)->Where('created_at','like', '%'.$date_check_2.'%')->first();
                            //        $b->adjustment_bill = $adjustment_bill;
                            // }

                            return response()->json([
                                'success' => true,
                                'data' => $data,
                                'consumer' => $consumer,
                                // 'obstacleCode' => $obstacleCode,
                                // 'issueCode' => $issueCode,
                                // 'meterLocation' => $meterLocation,
                                'route' => $route,
                                'subRoute' => $subRoute,
                                // 'reading' => $reading,

                                // 'tariff' => $tariff,
                                // 'highlow' => $highlow,
                                // 'duedate' => $dueDate,
                                // 'billnote' => $billNote,
                                // 'printer' => $printer,
                                // 'address' => $address,
                                'bill' => $bill,
                            ]);
                        } else {

                            //log
                            $new = new Log();
                            $new->user = $data->id;
                            $new->activity = "Failed User Login because app version doesnt match At Handheld ID: " . $handheld->id;
                            $new->month = Date('m');
                            $new->year = Date('Y');
                            $new->save();
                            return response()->json([
                                'success' => false,
                                'data' => 'Please Update Your App',
                                'require_update' => true,
                            ]);
                        }
                    } else {
                        //log
                        $new = new Log();
                        $new->user = $data->id;
                        $new->activity = "User Login At Handheld ID: " . $handheld->id;
                        $new->month = Date('m');
                        $new->year = Date('Y');
                        $new->save();

                        // $tariff = Price::where('effective_date', Price::max('effective_date'))->orderBy('effective_date','desc')->first();
                        // $highlow = Highlow::select('*')->first();
                        // $dueDate = DueDate::select('*')->first();
                        // $billNote = BillNote::select('*')->where('status',1)->first();
                        // $printer = Printer::where('branch_id',$data->branch_id)->get();
                        // $address = CompanyInformations::select('*')->get();

                        return response()->json([
                            'success' => true,
                            'data' => $data,
                            'consumer' => $consumer,
                            // 'obstacleCode' => $obstacleCode,
                            // 'issueCode' => $issueCode,
                            // 'meterLocation' => $meterLocation,
                            'route' => $route,
                            'subRoute' => $subRoute,
                            // 'reading' => $reading,

                            // 'tariff' => $tariff,
                            // 'highlow' => $highlow,
                            // 'duedate' => $dueDate,
                            // 'billnote' => $billNote,
                            // 'printer' => $printer,
                            // 'address' => $address,
                        ]);
                    }
                } else {
                    $new = new Log();
                    $new->user = $data->id;
                    $new->activity = "Login attempted at unregistered handheld";
                    $new->month = Date('m');
                    $new->year = Date('Y');
                    $new->save();
                    return response()->json([
                        'success' => false,
                        'data' => 'Please Register Your Handheld',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => 'Invalid username/password',
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'data' => 'Invalid username/password',
        ]);
    }

    public function getDownload(Request $request)
    {
        $data = AppVersion::where('status', 1)->orderBy('created_at', 'desc')->first();

        $file = public_path() . $data->url;

        return Response::download($file,
            $data->name . '.apk',
            ['Content-Type' => 'application/vnd.android.package-archive']);
    }

    public function downloadSetting(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required',
            'meter_reader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $handheld = Handheld::where('uuid', $request->uuid)->first();

        if ($handheld) {
            $date_check = date('Y-m');
            if (date('d') < 22) {
                $date_check = date('Y-m', strtotime('-28 days'));
            } else {
                $date_check = date('Y-m');
            }
            $issueCode = IssueCode::all();
            $obstacleCode = ObstacleCode::all();
            $meterLocation = MeterLocation::all();
            $tariff = Price::all();
            $highlow = Highlow::all();
            $dueDate = DueDate::select('*')->first();
            $billNote = BillNote::select('*')->where('status', 1)->first();
            $printer = Printer::where('branch_id', $handheld->branch_id)->get();
            $address = CompanyInformations::select('*')->get();
            $printable = Printable::find(1);

            $data = User::where('id', $request->meter_reader_id)->where('type', 4)->first();
            if ($data) {
                //log
                $new = new Log();
                $new->user = $data->id;
                $new->activity = "Settings downloaded successfully at Handheld ID: " . $handheld->id;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();
            }

            return response()->json([
                'success' => true,
                'obstacleCode' => $obstacleCode,
                'issueCode' => $issueCode,
                'meterLocation' => $meterLocation,
                'tariff' => $tariff,
                'highlow' => $highlow,
                'duedate' => $dueDate,
                'billnote' => $billNote,
                'printer' => $printer,
                'address' => $address,
                'printable' => $printable,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'data' => 'Please try again',
            ]);
        }
    }

    public function fileDownloadIndustrialAPI(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'error' => 'month and year required'];
        }

        $response = Http::post('https://gasmal009v2.gasmalaysia.com:8311/jderest/orchestrator/ORC_2109290008JDE', [
            "username" => env('JDE_USERNAME'),
            "password" => env('JDE_PASSWORD'),
            "Month" => $request->month,
            "Year" => $request->year,
        ]);

        //print_r("test"); return;
        $totalRowSaved = 0;
        if ($response->successful()) {

            $rows = $response->json()['DR_57_F57078']['rowset'];
            foreach ($rows as $row) {

                //dd($row['NEWCV']);
                $meter = Meter::where('meter_number', $row['MeterSerialNumber'])->first();
                $checkConsumer = Consumer::where('consumer_number', 'LIKE', '%' . $row['InternalOfftakerID'] . '%')->first();

                if (!$checkConsumer) {
                    $checkConsumer = new Consumer();
                }

                $checkConsumer->prefix = 1;
                $route = Route::select('id')->where('route', $row['BookNumber'])->first();
                if ($route) {
                    $checkConsumer->route_id = $route->id;
                } else {
                    $checkConsumer->route_id = $row['BookNumber'];
                }

                $checkConsumer->consumer_number = $row['InternalOfftakerID'];
                $checkConsumer->old_account_number = $row['OfftakerID'];
                $checkConsumer->tariff_code = 0;
                $checkConsumer->consumer_name = $row['BillingName'];
                $checkConsumer->customer_full_name = $row['BillingName'];
                $checkConsumer->consumer_address_1 = $row['AddressLine1'];
                $checkConsumer->consumer_address_2 = $row['AddressLine2'];
                $checkConsumer->consumer_address_3 = $row['AddressLine3'];
                $checkConsumer->area_code = $row['AreaCode'];
                $checkConsumer->offtaker_type = $row['OfftakerType'];

                $checkConsumer->consumer_type = 11; //hardcode to follow previouse type from UBIS

                $last_normal_reading_date_yyyy = substr($row['LastNormal ReadingDate'], 0, 4);
                $last_normal_reading_date_mm = substr($row['LastNormal ReadingDate'], 4, 2);
                $last_normal_reading_date_dd = substr($row['LastNormal ReadingDate'], 6, 2);

                $checkConsumer->last_normal_reading_date = date('Y-m-d', strtotime($last_normal_reading_date_yyyy . "/" . $last_normal_reading_date_mm . "/" . $last_normal_reading_date_dd));

                $last_bill_date_yyyy = substr($row['LastBillDate'], 0, 4);
                $last_bill_date_mm = substr($row['LastBillDate'], 4, 2);
                $last_bill_date_dd = substr($row['LastBillDate'], 6, 2);
                $checkConsumer->last_bill_date = date('Y-m-d', strtotime($last_bill_date_yyyy . "/" . $last_bill_date_mm . "/" . $last_bill_date_dd));

                $checkConsumer->pic_name = $row['PICName'];
                $checkConsumer->pic_code = $row['PICCode'];
                $checkConsumer->city_gate = $row['PICCode'];
                $checkConsumer->full_address = $row['LongAddress'];
                $checkConsumer->search_type = $row['SearchType'];

                $bool = $checkConsumer->save();
                if ($bool) {
                    $meter = Meter::where('meter_number', $row['MeterSerialNumber'])->first();
                    if (!$meter) {
                        $meter = new Meter();
                    }
                    $meter->prefix = 2;
                    $meter->consumer_id = $checkConsumer->id;
                    $meter->meter_number = $row['MeterSerialNumber'];
                    $meter->meter_sequence_number = $row['ReadingSequence'];
                    $meter->unit_measurement = $row['UOM'];
                    $meter->dial_length = $row['DialLength'];
                    $meterLocation = MeterLocation::select('id')
                        ->where('code_number', $row['MeterLocation'])
                        ->first();

                    if ($meterLocation) {
                        $meter->meter_location_id = $meterLocation->id;
                    }

                    $meter->meter_type = $row['MeterType'];
                    $meter->last_reading = $row['PreviousMeterReading'];
                    $meter->last_reading_status = $row['LastReadingStatus'];

                    if ($row['PreviousReadingDate'] != 'null') {
                        $last_reading_date_yyyy = substr($row['PreviousReadingDate'], 0, 4);
                        $last_reading_date_mm = substr($row['PreviousReadingDate'], 4, 2);
                        $last_reading_date_dd = substr($row['PreviousReadingDate'], 6, 2);
                        $meter->last_reading_date = date('Y-m-d', strtotime($last_reading_date_yyyy . "/" . $last_reading_date_mm . "/" . $last_reading_date_dd));
                    }

                    if ($row['LastNormal ReadingDate'] != 'null') {
                        $prev_reading_date_yyyy = substr($row['LastNormal ReadingDate'], 0, 4);
                        $prev_reading_date_mm = substr($row['LastNormal ReadingDate'], 4, 2);
                        $prev_reading_date_dd = substr($row['LastNormal ReadingDate'], 6, 2);
                        $meter->prev_reading_date = date('Y-m-d', strtotime($prev_reading_date_yyyy . "/" . $prev_reading_date_mm . "/" . $prev_reading_date_dd));
                    }

                    $meter->new_cv = doubleval($row['NEWCV']);
                    $meter->daily_average_consumption = $row['ReplacementConsumption'];
                    $meter->control_reading = $row['ControlReading'];
                    if ($row['ControlReadingDate'] != 'null') {
                        $control_reading_date_yyyy = substr($row['ControlReadingDate'], 0, 4);
                        $control_reading_date_mm = substr($row['ControlReadingDate'], 4, 2);
                        $control_reading_date_dd = substr($row['ControlReadingDate'], 6, 2);
                        $meter->last_reading_date = date('Y-m-d', strtotime($control_reading_date_yyyy . "/" . $control_reading_date_mm . "/" . $control_reading_date_dd));
                    }

                    $meter->temperature = $row['Temperature'];
                    $meter->pressure = $row['Pressure'];
                    $meter->corrected_volume = doubleval($row['CorrectedVolume']);
                    $meter->cf_factor = $row['CompressibilityFactor'];
                    $meter->actual_consumption = $row['ActualConsumption'];
                    $meter->network_address = $row['NetworkAddress'];
                    $meter->meter_status = $row['MeterStatus'];
                    $meter->download_flag = $row['DownloadFlag'];
                    $meter->corrector_yn = $row['CorrectorY/N'];
                    $meter->record_type = $row['RecordType'];
                    $meter->reading_month = $row['GenerationMonth'];
                    $meter->reading_year = $row['GenerationYear'];
                    $bool = $meter->save();
                    if ($bool) {
                        $totalRowSaved++;
                    }

                }
            }

            // response to JDE to indicate download successfull
            // $response = Http::post('https://gasmal009v2.gasmalaysia.com:8311/jderest/orchestrator/ORC_2109290002JDE', [
            //     "username" => env('JDE_USERNAME'),
            //     "password" =>  env('JDE_PASSWORD'),
            //     "mnMonth" => $request->month,
            //     "szFiscalYear" => $request->year,
            //     "szSMRSStatus" => "DS",
            //     "jdLastBillDate" => date('m/d/Y')
            // ]);

            return response()->json([
                'success' => true,
                'total' => count($rows),
                'save' => $totalRowSaved,
            ]);
        }
    }

    public function fileDownloadIndustrial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required|max:2048',
        ]);

        $name = $request->fileToUpload->getClientOriginalExtension();

        if ($validator->fails()) {
            return ['success' => false, 'error' => 'File not exist or wrong extension'];
        }

        $data = $request->file('fileToUpload');
        $reads = file($data);
        $index = 0;
        foreach ($reads as $read) {
            $index++;
            if (substr($read, 0, 1) == 1) {
                $prefix = substr($read, 0, 1);
                $route = substr($read, 1, 6);
                $consumer_number = substr($read, 7, 13);
                $old_account_number = substr($read, 20, 13);
                $tariff_code = substr($read, 33, 2);
                $consumer_name = substr($read, 35, 40);
                $consumer_address_1 = substr($read, 75, 40);
                $consumer_address_2 = substr($read, 115, 40);
                $consumer_address_3 = substr($read, 155, 40);
                $contract_number = substr($read, 195, 15);
                $bill_number = substr($read, 210, 8); //ni
                $area_code = substr($read, 218, 11);
                $consumer_type = substr($read, 229, 2);
                $consumer_status = substr($read, 231, 2);
                $bill_type = substr($read, 233, 1); // ni
                $arrears = substr($read, 234, 11);
                $deposit_type = substr($read, 245, 2);
                $deposit = substr($read, 247, 10);
                $last_normal_reading_date = substr($read, 257, 8);
                $last_bill_number = substr($read, 265, 8);
                $last_bill_date = substr($read, 273, 8);
                $last_bill_amount = substr($read, 281, 10);
                $last_payment_number = substr($read, 291, 8);
                $last_payment_date = substr($read, 299, 8);
                $last_payment_amount = substr($read, 307, 10);
                $last_bill_code = substr($read, 317, 1);
                $evc = substr($read, 318, 1);
                $interest_charges = substr($read, 319, 10);
                $rebate = substr($read, 329, 10);
                $spi = substr($read, 339, 5);
                $consecutive_estimate_amount = substr($read, 344, 10);
                $capital_contribution = substr($read, 354, 10);
                $fix_charges = substr($read, 364, 10);
                $caloric_value = substr($read, 374, 8);
                $special_rate = substr($read, 382, 7);
                $telephone_number = substr($read, 389, 10);
                $market_price = substr($read, 399, 7);
                $customer_full_name = substr($read, 406, 65);

                $checkConsumer = Consumer::where('consumer_number', 'LIKE', '%' . $consumer_number . '%')->first();

                if ($checkConsumer) {
                    $checkConsumer->prefix = $prefix;
                    $id = Route::select('id')->where('route', $route)->first();
                    $checkConsumer->route_id = $id->id;
                    $checkConsumer->consumer_number = $consumer_number;
                    $checkConsumer->old_account_number = $old_account_number;
                    $checkConsumer->tariff_code = $tariff_code;
                    $checkConsumer->consumer_name = $consumer_name;
                    $checkConsumer->consumer_address_1 = $consumer_address_1;
                    $checkConsumer->consumer_address_2 = $consumer_address_2;
                    $checkConsumer->consumer_address_3 = $consumer_address_3;
                    $checkConsumer->contract_number = $contract_number;
                    $checkConsumer->bill_number = $bill_number;
                    $checkConsumer->area_code = $area_code;
                    $checkConsumer->consumer_type = $consumer_type;
                    $checkConsumer->consumer_status = $consumer_status;
                    $checkConsumer->bill_type = $bill_type;
                    $checkConsumer->arrears = $arrears;
                    $checkConsumer->deposit_type = $deposit_type;
                    $checkConsumer->deposit = $deposit;

                    $last_normal_reading_date_dd = substr($last_normal_reading_date, 0, 2);
                    $last_normal_reading_date_mm = substr($last_normal_reading_date, 2, 2);
                    $last_normal_reading_date_yyyy = substr($last_normal_reading_date, 4, 4);
                    $checkConsumer->last_normal_reading_date = date('Y-m-d', strtotime($last_normal_reading_date_yyyy . "/" . $last_normal_reading_date_mm . "/" . $last_normal_reading_date_dd));

                    $checkConsumer->last_bill_number = $last_bill_number;

                    $last_bill_date_dd = substr($last_bill_date, 0, 2);
                    $last_bill_date_mm = substr($last_bill_date, 2, 2);
                    $last_bill_date_yyyy = substr($last_bill_date, 4, 4);
                    $checkConsumer->last_bill_date = date('Y-m-d', strtotime($last_bill_date_yyyy . "/" . $last_bill_date_mm . "/" . $last_bill_date_dd));

                    $checkConsumer->last_bill_amount = $last_bill_amount;
                    $checkConsumer->last_payment_number = intval($last_payment_number);

                    if ($index == 1) {
                        // dd($last_payment_date);
                    }
                    if ($last_payment_date == 00000000) {
                        // $checkConsumer->last_payment_date = null;
                    } else {
                        $last_payment_date_dd = substr($last_payment_date, 0, 2);
                        $last_payment_date_mm = substr($last_payment_date, 2, 2);
                        $last_payment_date_yyyy = substr($last_payment_date, 4, 4);
                        $checkConsumer->last_payment_date = date('Y-m-d', strtotime($last_payment_date_yyyy . "/" . $last_payment_date_mm . "/" . $last_payment_date_dd));
                    }

                    $checkConsumer->last_payment_amount = $last_payment_amount;
                    $checkConsumer->last_bill_code = $last_bill_code;
                    $checkConsumer->evc = $evc;
                    $checkConsumer->interest_charges = $interest_charges;
                    $checkConsumer->rebate = $rebate;
                    $checkConsumer->spi = $spi;
                    $checkConsumer->consecutive_estimate_amount = $consecutive_estimate_amount;
                    $checkConsumer->capital_contribution = $capital_contribution;
                    $checkConsumer->fix_charges = $fix_charges;
                    $checkConsumer->caloric_value = $caloric_value;
                    $checkConsumer->special_rate = intval($special_rate);
                    $checkConsumer->telephone_number = $telephone_number;
                    $checkConsumer->market_price = $market_price;
                    $checkConsumer->customer_full_name = $customer_full_name;
                    $bool = $checkConsumer->save();
                } else {

                    $new = new Consumer();
                    $new->prefix = $prefix;
                    $id = Route::select('id')->where('route', $route)->first();
                    $new->route_id = $id->id;
                    $new->consumer_number = $consumer_number;
                    $new->old_account_number = $old_account_number;
                    $new->tariff_code = $tariff_code;
                    $new->consumer_name = $consumer_name;
                    $new->consumer_address_1 = $consumer_address_1;
                    $new->consumer_address_2 = $consumer_address_2;
                    $new->consumer_address_3 = $consumer_address_3;
                    $new->contract_number = $contract_number;
                    $new->bill_number = $bill_number;
                    $new->area_code = $area_code;
                    $new->consumer_type = $consumer_type;
                    $new->consumer_status = $consumer_status;
                    $new->bill_type = $bill_type;
                    $new->arrears = $arrears;
                    $new->deposit_type = $deposit_type;
                    $new->deposit = $deposit;

                    $last_normal_reading_date_dd = substr($last_normal_reading_date, 0, 2);
                    $last_normal_reading_date_mm = substr($last_normal_reading_date, 2, 2);
                    $last_normal_reading_date_yyyy = substr($last_normal_reading_date, 4, 4);
                    $new->last_normal_reading_date = date('Y-m-d', strtotime($last_normal_reading_date_yyyy . "/" . $last_normal_reading_date_mm . "/" . $last_normal_reading_date_dd));

                    $new->last_bill_number = $last_bill_number;

                    $last_bill_date_dd = substr($last_bill_date, 0, 2);
                    $last_bill_date_mm = substr($last_bill_date, 2, 2);
                    $last_bill_date_yyyy = substr($last_bill_date, 4, 4);
                    $new->last_bill_date = date('Y-m-d', strtotime($last_bill_date_yyyy . "/" . $last_bill_date_mm . "/" . $last_bill_date_dd));

                    $new->last_bill_amount = $last_bill_amount;
                    $new->last_payment_number = intval($last_payment_number);

                    if ($index == 1) {
                        // dd($last_payment_date);
                    }
                    if ($last_payment_date == 00000000) {
                        // $new->last_payment_date = null;
                    } else {
                        $last_payment_date_dd = substr($last_payment_date, 0, 2);
                        $last_payment_date_mm = substr($last_payment_date, 2, 2);
                        $last_payment_date_yyyy = substr($last_payment_date, 4, 4);
                        $new->last_payment_date = date('Y-m-d', strtotime($last_payment_date_yyyy . "/" . $last_payment_date_mm . "/" . $last_payment_date_dd));
                    }

                    $new->last_payment_amount = $last_payment_amount;
                    $new->last_bill_code = $last_bill_code;
                    $new->evc = $evc;
                    $new->interest_charges = $interest_charges;
                    $new->rebate = $rebate;
                    $new->spi = $spi;
                    $new->consecutive_estimate_amount = $consecutive_estimate_amount;
                    $new->capital_contribution = $capital_contribution;
                    $new->fix_charges = $fix_charges;
                    $new->caloric_value = $caloric_value;
                    $new->special_rate = intval($special_rate);
                    $new->telephone_number = $telephone_number;
                    $new->market_price = $market_price;
                    $new->customer_full_name = $customer_full_name;
                    $bool = $new->save();
                    // $data->id;
                }
            } else if (substr($read, 0, 1) == 2) {
                $prefix = substr($read, 0, 1);
                $route = substr($read, 1, 6);
                $meter_number = substr($read, 7, 25);
                $unit_measurement = substr($read, 32, 1);
                $dial_length = substr($read, 33, 1);
                $meter_location = substr($read, 34, 2);
                $meter_type = substr($read, 36, 1);
                $last_reading = substr($read, 37, 9);
                $last_reading_date = substr($read, 46, 8);
                $last_reading_status = substr($read, 54, 2);
                $daily_average_consumption = substr($read, 56, 9);
                $meter_installation_date = substr($read, 65, 8);
                $replacement_consumption = substr($read, 73, 9);
                $disconnection_reading = substr($read, 82, 9);
                $disconnection_date = substr($read, 91, 8);
                $meter_reading_sequence = substr($read, 99, 4);
                $temperature = substr($read, 103, 5);
                $pressure = substr($read, 108, 6);
                $cf_factor = substr($read, 114, 7);
                $z_factor = substr($read, 121, 5);
                $adjustment_consumption = substr($read, 126, 10);

                $checkMeter = Meter::where('meter_number', 'LIKE', '%' . $meter_number . '%')->first();

                if ($checkMeter) {
                    $checkMeter->prefix = $prefix;
                    $checkMeter->meter_number = $meter_number;
                    $checkMeter->unit_measurement = $unit_measurement;
                    $checkMeter->dial_length = $dial_length;
                    $meter = MeterLocation::select('id')->where('code_number', $meter_location)->first();
                    $checkMeter->meter_location_id = $meter->id;
                    $checkMeter->meter_type = $meter_type;
                    $id = Consumer::select('id')->latest('id')->first();
                    $checkMeter->consumer_id = $id->id;
                    $checkMeter->last_reading = $last_reading;

                    $last_reading_date_dd = substr($last_reading_date, 0, 2);
                    $last_reading_date_mm = substr($last_reading_date, 2, 2);
                    $last_reading_date_yyyy = substr($last_reading_date, 4, 4);
                    $checkMeter->last_reading_date = date('Y-m-d', strtotime($last_reading_date_yyyy . "/" . $last_reading_date_mm . "/" . $last_reading_date_dd));

                    $checkMeter->last_reading_status = $last_reading_status;
                    $checkMeter->daily_average_consumption = $daily_average_consumption;

                    $meter_installation_date_dd = substr($meter_installation_date, 0, 2);
                    $meter_installation_date_mm = substr($meter_installation_date, 2, 2);
                    $meter_installation_date_yyyy = substr($meter_installation_date, 4, 4);
                    $checkMeter->meter_installation_date = date('Y-m-d', strtotime($meter_installation_date_yyyy . "/" . $meter_installation_date_mm . "/" . $meter_installation_date_dd));

                    $checkMeter->replacement_consumption = $replacement_consumption;
                    $checkMeter->disconnection_reading = $disconnection_reading;

                    if ($disconnection_date == 00000000) {
                        // $checkMeter->disconnection_date = null;
                    } else {
                        $disconnection_date_dd = substr($disconnection_date, 0, 2);
                        $disconnection_date_mm = substr($disconnection_date, 2, 2);
                        $disconnection_date_yyyy = substr($disconnection_date, 4, 4);
                        $checkMeter->disconnection_date = date('Y-m-d', strtotime($disconnection_date_yyyy . "/" . $disconnection_date_mm . "/" . $disconnection_date_dd));
                    }

                    $checkMeter->meter_reading_sequence = $meter_reading_sequence;
                    $checkMeter->temperature = $temperature;
                    $checkMeter->pressure = $pressure;
                    $checkMeter->cf_factor = $cf_factor;
                    $checkMeter->z_factor = $z_factor;
                    $checkMeter->adjustment_consumption = $adjustment_consumption;
                    $bool = $checkMeter->save();

                } else {

                    $new = new Meter();
                    $new->prefix = $prefix;
                    $new->meter_number = $meter_number;
                    $new->unit_measurement = $unit_measurement;
                    $new->dial_length = $dial_length;
                    $meter = MeterLocation::select('id')->where('code_number', $meter_location)->first();
                    $new->meter_location_id = $meter->id;
                    $new->meter_type = $meter_type;
                    $id = Consumer::select('id')->latest('id')->first();
                    $new->consumer_id = $id->id;
                    $new->last_reading = $last_reading;

                    $last_reading_date_dd = substr($last_reading_date, 0, 2);
                    $last_reading_date_mm = substr($last_reading_date, 2, 2);
                    $last_reading_date_yyyy = substr($last_reading_date, 4, 4);
                    $new->last_reading_date = date('Y-m-d', strtotime($last_reading_date_yyyy . "/" . $last_reading_date_mm . "/" . $last_reading_date_dd));

                    $new->last_reading_status = $last_reading_status;
                    $new->daily_average_consumption = $daily_average_consumption;

                    $meter_installation_date_dd = substr($meter_installation_date, 0, 2);
                    $meter_installation_date_mm = substr($meter_installation_date, 2, 2);
                    $meter_installation_date_yyyy = substr($meter_installation_date, 4, 4);
                    $new->meter_installation_date = date('Y-m-d', strtotime($meter_installation_date_yyyy . "/" . $meter_installation_date_mm . "/" . $meter_installation_date_dd));

                    $new->replacement_consumption = $replacement_consumption;
                    $new->disconnection_reading = $disconnection_reading;

                    if ($disconnection_date == 00000000) {
                        // $new->disconnection_date = null;
                    } else {
                        $disconnection_date_dd = substr($disconnection_date, 0, 2);
                        $disconnection_date_mm = substr($disconnection_date, 2, 2);
                        $disconnection_date_yyyy = substr($disconnection_date, 4, 4);
                        $new->disconnection_date = date('Y-m-d', strtotime($disconnection_date_yyyy . "/" . $disconnection_date_mm . "/" . $disconnection_date_dd));
                    }

                    $new->meter_reading_sequence = $meter_reading_sequence;
                    $new->temperature = $temperature;
                    $new->pressure = $pressure;
                    $new->cf_factor = $cf_factor;
                    $new->z_factor = $z_factor;
                    $new->adjustment_consumption = $adjustment_consumption;
                    $bool = $new->save();
                    // $data->id;

                    // $result1 = substr($read, 75, 40);
                    // $result2 = substr($read, 115, 40);
                    // $result3 = substr($read, 155, 40);
                    // echo '<br>'. $result1.' '.$result2.' '.$result3;
                }
            }
        }
        return ['success' => true, 'error' => 'Successfully Updated'];
    }

    public function fileUploadIndustrial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required',
        ]);

        $name = $request->fileToUpload->getClientOriginalExtension();

        if ($validator->fails()) {
            return ['success' => false, 'error' => 'File not exist or wrong extension'];
        }

        $data = $request->file('fileToUpload');
        $reads = file($data);
        $index = 0;

        $temp = 0;
        foreach ($reads as $read) {
            if (substr($read, 0, 1) == 1) {
                $date = substr($read, 24, 8);
                $date_dd = substr($date, 0, 2);
                $date_mm = substr($date, 2, 2);
                $date_yyyy = substr($date, 4, 4);
                $temp = date('Y-m-d', strtotime($date_yyyy . "/" . $date_mm . "/" . $date_dd));
                // dd($temp);
            } else if (substr($read, 0, 1) == 2) {

                $new = new Reading();

                $check = Meter::Where('meter_number', 'like', '%' . substr($read, 22, 25) . '%')->first();

                if ($check) {
                    $new->meter_id = $check->id;
                    $new->reading = substr($read, 56, 9);
                    $new->current_consumption = substr($read, 65, 9);
                    $new->issue_code_id = 4;
                    $new->accepted1 = 2;
                    $new->accepted2 = 2;
                    $new->created_at = $temp;
                    $new->save();
                }

            }
        }
        return ['success' => true, 'data' => 'Successfully Updated'];
    }

    public function fileDownloadResidence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required|max:2048',
        ]);

        $name = $request->fileToUpload->getClientOriginalExtension();

        if ($validator->fails() || $name != 'bak') {
            return ['success' => false, 'error' => 'File not exist or wrong extension'];
        }

        $data = $request->file('fileToUpload');
        $reads = file($data);
        $index = 0;
        foreach ($reads as $read) {
            if (substr($read, 0, 1) == 1) {
                $prefix = substr($read, 0, 1);
                $route = substr($read, 1, 6);
                $consumer_number = substr($read, 7, 11);
                $old_account_number = substr($read, 18, 14);
                $tariff_code = substr($read, 32, 2);
                $consumer_name = substr($read, 34, 50);
                $consumer_address_1 = substr($read, 84, 60);
                $consumer_address_2 = substr($read, 144, 60);
                $consumer_address_3 = substr($read, 204, 60);
                $postal_code = substr($read, 264, 5); // ni x de
                $contract_number = substr($read, 269, 15);
                $bill_number = substr($read, 284, 8); //ni x de
                $consumer_type = substr($read, 292, 2);
                $area_code = substr($read, 294, 9);
                $supply_type = substr($read, 303, 2); // ni x de
                $consumer_status = substr($read, 305, 2);
                $bill_type = substr($read, 307, 1); // ni x de
                $arrears = substr($read, 308, 11);
                $deposit_type = substr($read, 319, 2);
                $deposit = substr($read, 321, 10);
                $last_normal_reading_date = substr($read, 331, 8);
                $last_bill_number = substr($read, 339, 8);
                $last_bill_date = substr($read, 347, 8);
                $last_bill_amount = substr($read, 355, 10);
                $last_payment_number = substr($read, 365, 8);
                $last_payment_date = substr($read, 373, 8);
                $last_payment_amount = substr($read, 381, 10);
                $last_bill_code = substr($read, 391, 1);
                $download_date = substr($read, 392, 8); // ni x de
                $postal_address_1 = substr($read, 400, 60); // ni x de
                $postal_address_2 = substr($read, 460, 60); // ni x de
                $postal_address_3 = substr($read, 520, 60); // ni x de
                $postal_address_post_code = substr($read, 580, 5); // ni x de
                $evc = substr($read, 585, 1);
                $interest_charges = substr($read, 586, 10);
                $rebate = substr($read, 596, 10);
                $spi = substr($read, 606, 5);
                $consecutive_estimate_count = substr($read, 611, 2); // NI X DE
                $consecutive_estimate_amount = substr($read, 613, 10);
                $capital_contribution = substr($read, 623, 10);
                $fix_charges = substr($read, 633, 10);
                $caloric_value = substr($read, 643, 10);
                $special_rate = substr($read, 653, 7);
                $telephone_number = substr($read, 660, 10);
                $provision_rate = substr($read, 670, 7); //ni x de
                $installation_amount = substr($read, 677, 11); //ni x de
                $inspection_amount = substr($read, 688, 11); //ni x de
                $reconnection_amount = substr($read, 699, 11); //ni x de
                $additional_deposit = substr($read, 710, 11); //ni x de
                $gst_relief_indicator = substr($read, 721, 1); //ni x de
                $gst_register_indicator = substr($read, 722, 1); //ni x de
                $ng_lpg_company_name = substr($read, 723, 60); //ni x de
                $ng_lpg_company_address = substr($read, 783, 60); //ni x de
                $ng_lpg_company_registration_no = substr($read, 843, 30); //ni x de
                $ng_lpg_gst_number = substr($read, 873, 30); //ni x de
                $ng_lpg_telephone_number = substr($read, 903, 30); //ni x de
                $ng_lpg_fax_number = substr($read, 933, 30); //ni x de
                $consecutive_gst_amount = substr($read, 963, 10); //ni x de
                $gst_hq_name = substr($read, 973, 55); //ni x de
                $retail_license_name = substr($read, 1028, 50); //ni x de
                $landed_property_indicator = substr($read, 1078, 1); //ni x de

                if ($landed_property_indicator == 1 || $landed_property_indicator == 'Y' || $landed_property_indicator == 'y') {

                    $checkConsumer = Consumer::where('consumer_number', 'LIKE', '%' . $consumer_number . '%')->first();

                    if ($checkConsumer) {
                        $checkConsumer->prefix = $prefix;
                        $id = Route::select('id')->where('route', $route)->first(); //need to change later
                        $checkConsumer->route_id = $id->id; //need to change later
                        $checkConsumer->consumer_number = $consumer_number;
                        $checkConsumer->old_account_number = $old_account_number;
                        $checkConsumer->tariff_code = $tariff_code;
                        $checkConsumer->consumer_name = $consumer_name;
                        $checkConsumer->consumer_address_1 = $consumer_address_1;
                        $checkConsumer->consumer_address_2 = $consumer_address_2;
                        $checkConsumer->consumer_address_3 = $consumer_address_3;
                        $checkConsumer->postal_code = $postal_code; //added
                        $checkConsumer->contract_number = $contract_number;
                        $checkConsumer->bill_number = $bill_number; //added
                        $checkConsumer->area_code = $area_code;
                        $checkConsumer->supply_type = $supply_type; //added
                        $checkConsumer->consumer_type = $consumer_type;
                        $checkConsumer->consumer_status = $consumer_status;
                        $checkConsumer->bill_type = $bill_type; //added
                        $checkConsumer->arrears = $arrears;
                        $checkConsumer->deposit_type = $deposit_type;
                        $checkConsumer->deposit = $deposit;

                        $last_normal_reading_date_dd = substr($last_normal_reading_date, 0, 2);
                        $last_normal_reading_date_mm = substr($last_normal_reading_date, 2, 2);
                        $last_normal_reading_date_yyyy = substr($last_normal_reading_date, 4, 4);
                        $checkConsumer->last_normal_reading_date = date('Y-m-d', strtotime($last_normal_reading_date_yyyy . "/" . $last_normal_reading_date_mm . "/" . $last_normal_reading_date_dd));

                        $checkConsumer->last_bill_number = $last_bill_number;

                        $last_bill_date_dd = substr($last_bill_date, 0, 2);
                        $last_bill_date_mm = substr($last_bill_date, 2, 2);
                        $last_bill_date_yyyy = substr($last_bill_date, 4, 4);
                        $checkConsumer->last_bill_date = date('Y-m-d', strtotime($last_bill_date_yyyy . "/" . $last_bill_date_mm . "/" . $last_bill_date_dd));

                        $checkConsumer->last_bill_amount = $last_bill_amount;
                        $checkConsumer->last_payment_number = intval($last_payment_number);

                        if ($index == 1) {
                            // dd($last_payment_date);
                        }
                        if ($last_payment_date == 00000000) {
                            // $checkConsumer->last_payment_date = null;
                        } else {
                            $last_payment_date_dd = substr($last_payment_date, 0, 2);
                            $last_payment_date_mm = substr($last_payment_date, 2, 2);
                            $last_payment_date_yyyy = substr($last_payment_date, 4, 4);
                            $checkConsumer->last_payment_date = date('Y-m-d', strtotime($last_payment_date_yyyy . "/" . $last_payment_date_mm . "/" . $last_payment_date_dd));
                        }

                        $checkConsumer->last_payment_amount = $last_payment_amount;
                        $checkConsumer->last_bill_code = $last_bill_code;

                        $download_date_dd = substr($download_date, 0, 2);
                        $download_date_mm = substr($download_date, 2, 2);
                        $download_date_yyyy = substr($download_date, 4, 4);
                        $checkConsumer->download_date = date('Y-m-d', strtotime($download_date_yyyy . "/" . $download_date_mm . "/" . $download_date_dd));

                        $checkConsumer->postal_address_1 = $postal_address_1;
                        $checkConsumer->postal_address_2 = $postal_address_2;
                        $checkConsumer->postal_address_3 = $postal_address_3;
                        $checkConsumer->postal_address_post_code = $postal_address_post_code;
                        $checkConsumer->evc = $evc;
                        $checkConsumer->interest_charges = $interest_charges;
                        $checkConsumer->rebate = $rebate;
                        $checkConsumer->spi = $spi;
                        $checkConsumer->consecutive_estimate_count = $consecutive_estimate_count;
                        $checkConsumer->consecutive_estimate_amount = $consecutive_estimate_amount;
                        $checkConsumer->capital_contribution = $capital_contribution;
                        $checkConsumer->fix_charges = $fix_charges;
                        $checkConsumer->caloric_value = $caloric_value;
                        $checkConsumer->special_rate = intval($special_rate);
                        $checkConsumer->telephone_number = $telephone_number;

                        $checkConsumer->provision_rate = $provision_rate; //ni x de
                        $checkConsumer->installation_amount = $installation_amount; //ni x de
                        $checkConsumer->inspection_amount = $inspection_amount; //ni x de
                        $checkConsumer->reconnection_amount = $reconnection_amount; //ni x de
                        $checkConsumer->additional_deposit = $additional_deposit; //ni x de
                        $checkConsumer->gst_relief_indicator = $gst_relief_indicator; //ni x de
                        $checkConsumer->gst_register_indicator = $gst_register_indicator; //ni x de
                        $checkConsumer->ng_lpg_company_name = $ng_lpg_company_name; //ni x de
                        $checkConsumer->ng_lpg_company_address = $ng_lpg_company_address; //ni x de
                        $checkConsumer->ng_lpg_company_registration_no = $ng_lpg_company_registration_no; //ni x de
                        $checkConsumer->ng_lpg_gst_number = $ng_lpg_gst_number; //ni x de
                        $checkConsumer->ng_lpg_telephone_number = $ng_lpg_telephone_number; //ni x de
                        $checkConsumer->ng_lpg_fax_number = $ng_lpg_fax_number; //ni x de
                        $checkConsumer->consecutive_gst_amount = $consecutive_gst_amount; //ni x de
                        $checkConsumer->gst_hq_name = $gst_hq_name; //ni x de
                        $checkConsumer->retail_license_name = $retail_license_name; //ni x de
                        $checkConsumer->landed_property_indicator = $landed_property_indicator; //ni x de
                        // $checkConsumer->market_price = $market_price; // x de
                        $checkConsumer->customer_full_name = $consumer_name; // add on
                        $bool = $checkConsumer->save();
                    } else {

                        //start assign
                        $new = new Consumer();
                        $new->prefix = $prefix;
                        $id = Route::select('id')->where('route', $route)->first(); //need to change later
                        $new->route_id = $id->id; //need to change later
                        $new->consumer_number = $consumer_number;
                        $new->old_account_number = $old_account_number;
                        $new->tariff_code = $tariff_code;
                        $new->consumer_name = $consumer_name;
                        $new->consumer_address_1 = $consumer_address_1;
                        $new->consumer_address_2 = $consumer_address_2;
                        $new->consumer_address_3 = $consumer_address_3;
                        $new->postal_code = $postal_code; //added
                        $new->contract_number = $contract_number;
                        $new->bill_number = $bill_number; //added
                        $new->area_code = $area_code;
                        $new->supply_type = $supply_type; //added
                        $new->consumer_type = $consumer_type;
                        $new->consumer_status = $consumer_status;
                        $new->bill_type = $bill_type; //added
                        $new->arrears = $arrears;
                        $new->deposit_type = $deposit_type;
                        $new->deposit = $deposit;

                        $last_normal_reading_date_dd = substr($last_normal_reading_date, 0, 2);
                        $last_normal_reading_date_mm = substr($last_normal_reading_date, 2, 2);
                        $last_normal_reading_date_yyyy = substr($last_normal_reading_date, 4, 4);
                        $new->last_normal_reading_date = date('Y-m-d', strtotime($last_normal_reading_date_yyyy . "/" . $last_normal_reading_date_mm . "/" . $last_normal_reading_date_dd));

                        $new->last_bill_number = $last_bill_number;

                        $last_bill_date_dd = substr($last_bill_date, 0, 2);
                        $last_bill_date_mm = substr($last_bill_date, 2, 2);
                        $last_bill_date_yyyy = substr($last_bill_date, 4, 4);
                        $new->last_bill_date = date('Y-m-d', strtotime($last_bill_date_yyyy . "/" . $last_bill_date_mm . "/" . $last_bill_date_dd));

                        $new->last_bill_amount = $last_bill_amount;
                        $new->last_payment_number = intval($last_payment_number);

                        if ($index == 1) {
                            // dd($last_payment_date);
                        }
                        if ($last_payment_date == 00000000) {
                            // $new->last_payment_date = null;
                        } else {
                            $last_payment_date_dd = substr($last_payment_date, 0, 2);
                            $last_payment_date_mm = substr($last_payment_date, 2, 2);
                            $last_payment_date_yyyy = substr($last_payment_date, 4, 4);
                            $new->last_payment_date = date('Y-m-d', strtotime($last_payment_date_yyyy . "/" . $last_payment_date_mm . "/" . $last_payment_date_dd));
                        }

                        $new->last_payment_amount = $last_payment_amount;
                        $new->last_bill_code = $last_bill_code;

                        $download_date_dd = substr($download_date, 0, 2);
                        $download_date_mm = substr($download_date, 2, 2);
                        $download_date_yyyy = substr($download_date, 4, 4);
                        $new->download_date = date('Y-m-d', strtotime($download_date_yyyy . "/" . $download_date_mm . "/" . $download_date_dd));

                        $new->postal_address_1 = $postal_address_1;
                        $new->postal_address_2 = $postal_address_2;
                        $new->postal_address_3 = $postal_address_3;
                        $new->postal_address_post_code = $postal_address_post_code;
                        $new->evc = $evc;
                        $new->interest_charges = $interest_charges;
                        $new->rebate = $rebate;
                        $new->spi = $spi;
                        $new->consecutive_estimate_count = $consecutive_estimate_count;
                        $new->consecutive_estimate_amount = $consecutive_estimate_amount;
                        $new->capital_contribution = $capital_contribution;
                        $new->fix_charges = $fix_charges;
                        $new->caloric_value = $caloric_value;
                        $new->special_rate = intval($special_rate);
                        $new->telephone_number = $telephone_number;

                        $new->provision_rate = $provision_rate; //ni x de
                        $new->installation_amount = $installation_amount; //ni x de
                        $new->inspection_amount = $inspection_amount; //ni x de
                        $new->reconnection_amount = $reconnection_amount; //ni x de
                        $new->additional_deposit = $additional_deposit; //ni x de
                        $new->gst_relief_indicator = $gst_relief_indicator; //ni x de
                        $new->gst_register_indicator = $gst_register_indicator; //ni x de
                        $new->ng_lpg_company_name = $ng_lpg_company_name; //ni x de
                        $new->ng_lpg_company_address = $ng_lpg_company_address; //ni x de
                        $new->ng_lpg_company_registration_no = $ng_lpg_company_registration_no; //ni x de
                        $new->ng_lpg_gst_number = $ng_lpg_gst_number; //ni x de
                        $new->ng_lpg_telephone_number = $ng_lpg_telephone_number; //ni x de
                        $new->ng_lpg_fax_number = $ng_lpg_fax_number; //ni x de
                        $new->consecutive_gst_amount = $consecutive_gst_amount; //ni x de
                        $new->gst_hq_name = $gst_hq_name; //ni x de
                        $new->retail_license_name = $retail_license_name; //ni x de
                        $new->landed_property_indicator = $landed_property_indicator; //ni x de
                        // $new->market_price = $market_price; // x de
                        $new->customer_full_name = $consumer_name; // add on
                        $bool = $new->save();
                    }
                }
            } else if (substr($read, 0, 1) == 2) {
                if ($landed_property_indicator == 1 || $landed_property_indicator == 'Y' || $landed_property_indicator == 'y') {

                    $prefix = substr($read, 0, 1);
                    $route = substr($read, 1, 6); // ni x de
                    $meter_number = substr($read, 7, 15);
                    $meter_sequence_number = substr($read, 22, 10); // ni x de
                    $unit_measurement = substr($read, 32, 1);
                    $dial_length = substr($read, 33, 1);
                    $meter_location = substr($read, 34, 2);
                    $meter_type = substr($read, 36, 1);
                    $last_reading = substr($read, 37, 9);
                    $last_reading_date = substr($read, 46, 8);
                    $last_reading_status = substr($read, 54, 2);
                    $daily_average_consumption = substr($read, 56, 9);
                    $meter_installation_date = substr($read, 65, 8);
                    $replacement_consumption = substr($read, 73, 9);
                    $control_reading = substr($read, 82, 9); // ni x de
                    $control_reading_date = substr($read, 91, 8); // ni x de
                    $meter_status = substr($read, 99, 2); // ni x de
                    $meter_reading_sequence = substr($read, 101, 4);
                    $temperature = substr($read, 105, 5);
                    $pressure = substr($read, 110, 6);
                    $cf_factor = substr($read, 116, 7);
                    $z_factor = substr($read, 123, 5);

                    $checkMeter = Meter::where('meter_number', 'LIKE', '%' . $meter_number . '%')->first();

                    if ($checkMeter) {
                        $checkMeter->prefix = $prefix;
                        // $checkMeter->route = $route; //added ni x de
                        $checkMeter->meter_number = $meter_number;
                        $checkMeter->meter_sequence_number = $meter_sequence_number; //added
                        $checkMeter->unit_measurement = $unit_measurement;
                        $checkMeter->dial_length = $dial_length;
                        $meter = MeterLocation::select('id')->where('code_number', $meter_location)->first();
                        $checkMeter->meter_location_id = $meter->id;
                        $checkMeter->meter_type = $meter_type;
                        $id = Consumer::select('id')->latest('id')->first();
                        $checkMeter->consumer_id = $id->id;
                        $checkMeter->last_reading = $last_reading;

                        $last_reading_date_dd = substr($last_reading_date, 0, 2);
                        $last_reading_date_mm = substr($last_reading_date, 2, 2);
                        $last_reading_date_yyyy = substr($last_reading_date, 4, 4);
                        $checkMeter->last_reading_date = date('Y-m-d', strtotime($last_reading_date_yyyy . "/" . $last_reading_date_mm . "/" . $last_reading_date_dd));

                        $checkMeter->last_reading_status = $last_reading_status;
                        $checkMeter->daily_average_consumption = $daily_average_consumption;

                        $meter_installation_date_dd = substr($meter_installation_date, 0, 2);
                        $meter_installation_date_mm = substr($meter_installation_date, 2, 2);
                        $meter_installation_date_yyyy = substr($meter_installation_date, 4, 4);
                        $checkMeter->meter_installation_date = date('Y-m-d', strtotime($meter_installation_date_yyyy . "/" . $meter_installation_date_mm . "/" . $meter_installation_date_dd));

                        $checkMeter->replacement_consumption = $replacement_consumption;
                        // $checkMeter->disconnection_reading = $disconnection_reading;  // ni x de

                        // if ($disconnection_date == 00000000)  // ni x de
                        // {
                        //     // $checkMeter->disconnection_date = null;
                        // }
                        // else
                        // {
                        //     $disconnection_date_dd = substr($disconnection_date, 0, 2);
                        //     $disconnection_date_mm = substr($disconnection_date, 2, 2);
                        //     $disconnection_date_yyyy = substr($disconnection_date, 4, 4);
                        //     $checkMeter->disconnection_date = date('Y-m-d',strtotime($disconnection_date_yyyy."/".$disconnection_date_mm."/".$disconnection_date_dd));
                        // }
                        $checkMeter->control_reading = $control_reading; // addeed

                        $control_reading_date_dd = substr($control_reading_date, 0, 2);
                        $control_reading_date_mm = substr($control_reading_date, 2, 2);
                        $control_reading_date_yyyy = substr($control_reading_date, 4, 4);
                        $checkMeter->control_reading_date = date('Y-m-d', strtotime($control_reading_date_yyyy . "/" . $control_reading_date_mm . "/" . $control_reading_date_dd));
                        // $checkMeter->control_reading_date = $control_reading_date;  // added
                        $checkMeter->meter_status = $meter_status; // added

                        $checkMeter->meter_reading_sequence = $meter_reading_sequence;
                        $checkMeter->temperature = $temperature;
                        $checkMeter->pressure = $pressure;
                        $checkMeter->cf_factor = $cf_factor;
                        $checkMeter->z_factor = $z_factor;
                        // $checkMeter->adjustment_consumption = $adjustment_consumption;  // ni x de
                        $bool = $checkMeter->save();
                    } else {
                        //start assign
                        $new = new Meter();
                        $new->prefix = $prefix;
                        // $new->route = $route; //added ni x de
                        $new->meter_number = $meter_number;
                        $new->meter_sequence_number = $meter_sequence_number; //added
                        $new->unit_measurement = $unit_measurement;
                        $new->dial_length = $dial_length;
                        $meter = MeterLocation::select('id')->where('code_number', $meter_location)->first();
                        $new->meter_location_id = $meter->id;
                        $new->meter_type = $meter_type;
                        $id = Consumer::select('id')->latest('id')->first();
                        $new->consumer_id = $id->id;
                        $new->last_reading = $last_reading;

                        $last_reading_date_dd = substr($last_reading_date, 0, 2);
                        $last_reading_date_mm = substr($last_reading_date, 2, 2);
                        $last_reading_date_yyyy = substr($last_reading_date, 4, 4);
                        $new->last_reading_date = date('Y-m-d', strtotime($last_reading_date_yyyy . "/" . $last_reading_date_mm . "/" . $last_reading_date_dd));

                        $new->last_reading_status = $last_reading_status;
                        $new->daily_average_consumption = $daily_average_consumption;

                        $meter_installation_date_dd = substr($meter_installation_date, 0, 2);
                        $meter_installation_date_mm = substr($meter_installation_date, 2, 2);
                        $meter_installation_date_yyyy = substr($meter_installation_date, 4, 4);
                        $new->meter_installation_date = date('Y-m-d', strtotime($meter_installation_date_yyyy . "/" . $meter_installation_date_mm . "/" . $meter_installation_date_dd));

                        $new->replacement_consumption = $replacement_consumption;
                        // $new->disconnection_reading = $disconnection_reading;  // ni x de

                        // if ($disconnection_date == 00000000)  // ni x de
                        // {
                        //     // $new->disconnection_date = null;
                        // }
                        // else
                        // {
                        //     $disconnection_date_dd = substr($disconnection_date, 0, 2);
                        //     $disconnection_date_mm = substr($disconnection_date, 2, 2);
                        //     $disconnection_date_yyyy = substr($disconnection_date, 4, 4);
                        //     $new->disconnection_date = date('Y-m-d',strtotime($disconnection_date_yyyy."/".$disconnection_date_mm."/".$disconnection_date_dd));
                        // }
                        $new->control_reading = $control_reading; // addeed

                        $control_reading_date_dd = substr($control_reading_date, 0, 2);
                        $control_reading_date_mm = substr($control_reading_date, 2, 2);
                        $control_reading_date_yyyy = substr($control_reading_date, 4, 4);
                        $new->control_reading_date = date('Y-m-d', strtotime($control_reading_date_yyyy . "/" . $control_reading_date_mm . "/" . $control_reading_date_dd));
                        // $new->control_reading_date = $control_reading_date;  // added
                        $new->meter_status = $meter_status; // added

                        $new->meter_reading_sequence = $meter_reading_sequence;
                        $new->temperature = $temperature;
                        $new->pressure = $pressure;
                        $new->cf_factor = $cf_factor;
                        $new->z_factor = $z_factor;
                        // $new->adjustment_consumption = $adjustment_consumption;  // ni x de
                        $bool = $new->save();

                    }
                }
            }
        }
        return ['success' => true, 'data' => 'Successfully Updated'];
    }

    public function fileDownloadTariff(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required|max:2048',
        ]);

        $name = $request->fileToUpload->getClientOriginalExtension();

        if ($validator->fails() || $name != 'DAT') {
            return ['success' => false, 'error' => 'File not exist or wrong extension'];
        }

        $data = $request->file('fileToUpload');
        $reads = file($data);
        $index = 0;
        foreach ($reads as $read) {
            $tariff_code = substr($read, 0, 2);
            $effective_date = substr($read, 2, 8);
            $staged_quantity_1 = substr($read, 10, 10);
            $staged_rate_1 = substr($read, 20, 10);
            $staged_quantity_2 = substr($read, 30, 10);
            $staged_rate_2 = substr($read, 40, 10);
            $staged_quantity_3 = substr($read, 50, 10);
            $staged_rate_3 = substr($read, 60, 10);
            $monthly_minimum_charge = substr($read, 70, 10);
            $tariff_description = substr($read, 80, 60);
            $meter_rental = substr($read, 140, 10);
            $division_factor = substr($read, 150, 10);
            $gst_taxable_rate = substr($read, 160, 6);
            $multiplier = substr($read, 166, 5);

            $new = new Price();
            $new->code = $tariff_code;

            $effective_date_dd = substr($effective_date, 0, 2);
            $effective_date_mm = substr($effective_date, 2, 2);
            $effective_date_yyyy = substr($effective_date, 4, 4);
            $new->effective_date = date('Y-m-d', strtotime($effective_date_yyyy . "/" . $effective_date_mm . "/" . $effective_date_dd));

            // $new->effective_date = $effective_date;
            $new->staged_quantity_1 = intval($staged_quantity_1);
            $new->staged_rate_1 = intval($staged_rate_1) / 100;
            $new->staged_quantity_2 = intval($staged_quantity_2);
            $new->staged_rate_2 = intval($staged_rate_2) / 100;
            $new->staged_quantity_3 = intval($staged_quantity_3);
            $new->staged_rate_3 = intval($staged_rate_3) / 100;
            $new->monthly_minimum_charge = intval($monthly_minimum_charge);
            $new->tariff_description = $tariff_description;
            $new->meter_rental = intval($meter_rental);
            $new->division_factor = intval($division_factor);
            $new->gst_taxable_rate = intval($gst_taxable_rate);
            $new->multiplier = intval($multiplier);
            $new->save();
        }

        return ['success' => true, 'data' => 'Successfully Updated'];

    }

    public function saveReading(Request $request)
    {

        $reading_id = 0;
        $validator = Validator::make($request->all(), [
            'reading' => 'required',
            'meter_id' => 'required',
            'image' => 'required',
            'issue_code_id' => 'required',
            'obstacle_code_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'meter_reader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $meterId = $request->meter_id;
        $reading = $request->reading;
        $image = $request->image;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $meterReaderId = $request->meter_reader_id;

        $meter = Meter::find($meterId);

        $max_dial_string = '';
        $max_dial_int = 0;
        for ($j = 0; $j < $meter->dial_length; $j++) {
            $max_dial_string .= '9';
        }
        if ($max_dial_string != '') {
            $max_dial_int = intval($max_dial_string);
        }
        $current_consumption = 0;
        if ($reading < $meter->last_reading) {
            $current_consumption = $reading + ($max_dial_int - $meter->last_reading);
        } else {
            $current_consumption = $reading - $meter->last_reading;
        }

        $new = new Reading();
        $new->meter_id = $meterId;
        $new->billable = 0;
        $new->reading = $reading;

        $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
        $new->current_consumption = $consumption;
        // if ($meter->unit_measurement == 0) {
        //     $new->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
        // } else {
        //     $new->current_consumption = $current_consumption;
        // }

        $new->accepted1 = 0;
        $new->accepted2 = 0;
        $new->image = $image;
        $new->issue_code_id = $request->issue_code_id;
        $new->obstacle_code_id = $request->obstacle_code_id;
        $new->daily_average_consumption = $meter->daily_average_consumption;
        $new->replacement_consumption = $meter->replacement_consumption;
        $new->disconnection_reading = $meter->disconnection_reading;
        $new->disconnection_date = $meter->disconnection_date;
        $new->meter_reading_sequence = $meter->meter_reading_sequence;
        $new->temperature = $meter->temperature;
        $new->pressure = $meter->pressure;
        $new->adjustment_consumption = $meter->adjustment_consumption;
        $new->meter_reader_id = $meterReaderId;
        $new->supervisor_branch_id = 0;
        $new->supervisor_id = 0;
        $new->latitude = $latitude;
        $new->longitude = $longitude;

        //azmi add
        $new->reading_date = date('Y-m-d H:i:s');
        $new->reading_month = $meter->reading_month;
        $new->reading_year = $meter->reading_year;
        $new->new_cv = $meter->new_cv;
        $new->q_min = $meter->q_min;
        $new->corrected_volume = $meter->corrected_volume;
        $new->adjustment_consumption = $meter->adjustment_consumption;
        // $new->cf_factor = $meter->cf_factor;
        // $new->z_factor = $meter->z_factor;

        $date_check = date('Y-m-d H:i:s');
        if (date('d') < 22) {
            $date_check = date('Y-m-d H:i:s', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m-d H:i:s');
        }

        $new->timestamps = false;
        $bool = $new->save();
        $new->created_at = $date_check;
        $bool = $new->save();

        if ($bool) {
            $reading_id = $new->id;
            //log
            $new = new Log();
            $new->user = $meterReaderId;
            $new->activity = "Saved Reading for #Meter: " . $meter->meter_number;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                    $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                    $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                    $highlowPercent = ConsumptionVariant::find($meter->consumer_id);

                    if ($actualConsVar < $highlowPercent->low) {

                        $new = new Notification();
                        $new->title = 'Reading Too Low!';
                        $new->content = $meter->meter_number;
                        $new->meter_number = $meter->meter_number;
                        $new->meter_id = $meter->id;
                        $new->branch_id = $meterReaderId;
                        $new->save();

                    } elseif ($actualConsVar > $highlowPercent->high) {
                        $new = new Notification();
                        $new->title = 'Reading Too High!';
                        $new->content = $meter->meter_number;
                        $new->meter_number = $meter->meter_number;
                        $new->meter_id = $meter->id;
                        $new->branch_id = $meterReaderId;
                        $new->save();
                    }

                }
            }

            return ['success' => true, 'data' => $reading_id];
        } else {
            return ['success' => false, 'data' => 'Saving Error'];
        }
    }

    public function sendData(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'reading' => 'required',
            'bill' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $readings = json_decode($request->reading);
        $bills = json_decode($request->bill);
        $counter = 0;
        $counterBill = 0;
        // dd($readings[0]->meter_id);
        // dd(json_decode($request->reading));

        // $meterId = $request->readings->meter_id;
        // $reading = $request->readings->reading;
        // $image = $request->readings->image;
        // $issueCode = $request->readings->issue_code;
        // $obstacleCode = $request->readings->obstacle_code;
        // $latitude = $request->readings->latitude;
        // $longitude = $request->readings->longitude;
        // $meterReaderId = $request->readings->meter_reader_id;

        // $meter = Meter::find($meterId);

        if (count($readings) > 0) {
            // $counter = 0;
            for ($i = 0; $i < count($readings); $i++) {
                // print($readings[$i]->meter_id);
                $date_check = date('Y-m');
                if (date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $existing_reading = Reading::where('meter_id', $readings[$i]->meter_id)->where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();
                if ($existing_reading) {
                    $existing_reading->reading = $readings[$i]->reading; //

                    $meter = Meter::find($existing_reading->meter_id);

                    $max_dial_string = '';
                    $max_dial_int = 0;
                    for ($j = 0; $j < $meter->dial_length; $j++) {
                        $max_dial_string .= '9';
                    }
                    if ($max_dial_string != '') {
                        $max_dial_int = intval($max_dial_string);
                    }
                    $current_consumption = 0;
                    if ($readings[$i]->reading < $meter->last_reading) {
                        $current_consumption = $readings[$i]->reading + ($max_dial_int - $meter->last_reading);
                    } else {
                        $current_consumption = $readings[$i]->reading - $meter->last_reading;
                    }

                    // if ($meter->unit_measurement == 0) {
                    //     $existing_reading->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
                    // } else {
                    //     $existing_reading->current_consumption = $current_consumption;
                    // }
                    $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
                    $existing_reading->current_consumption = $consumption;

                    $existing_reading->image = $readings[$i]->image; //
                    $existing_reading->issue_code_id = $readings[$i]->issue_code_id; //
                    $existing_reading->obstacle_code_id = $readings[$i]->obstacle_code_id; //
                    $existing_reading->latitude = $readings[$i]->latitude; //
                    $existing_reading->longitude = $readings[$i]->longitude; //
                    $bool = $existing_reading->save();

                    if ($bool) {

                        $meter = Meter::where('id', $readings[$i]->meter_id)->first();
                        if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                            $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                            $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                            $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                            if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                                $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                                $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                                $highlowPercent = ConsumptionVariant::find($meter->consumer_id);

                                if ($actualConsVar < $highlowPercent->low) {

                                    $new = new Notification();
                                    $new->title = 'Reading Too Low!';
                                    $new->content = $meter->meter_number;
                                    $new->meter_number = $meter->meter_number;
                                    $new->meter_id = $meter->id;
                                    $new->updated_by = $request->meter_reader_id;
                                    $new->save();

                                } elseif ($actualConsVar > $highlowPercent->high) {
                                    $new = new Notification();
                                    $new->title = 'Reading Too High!';
                                    $new->content = $meter->meter_number;
                                    $new->meter_number = $meter->meter_number;
                                    $new->meter_id = $meter->id;
                                    $new->updated_by = $request->meter_reader_id;
                                    $new->save();
                                }

                            }
                        }

                        // return response()->json([
                        //     'success' => true,
                        //     'data' => $reading,
                        // ]);
                        $counter++;
                    } else {
                        // return response()->json([
                        // 'success' => false,
                        // 'data' => $validator->messages()->first(),
                        // ]);
                    }
                } else {
                    $meter = Meter::find($readings[$i]->meter_id);

                    $max_dial_string = '';
                    $max_dial_int = 0;
                    for ($j = 0; $j < $meter->dial_length; $j++) {
                        $max_dial_string .= '9';
                    }
                    if ($max_dial_string != '') {
                        $max_dial_int = intval($max_dial_string);
                    }
                    $current_consumption = 0;
                    if ($readings[$i]->reading < $meter->last_reading) {
                        $current_consumption = $readings[$i]->reading + ($max_dial_int - $meter->last_reading);
                    } else {
                        $current_consumption = $readings[$i]->reading - $meter->last_reading;
                    }

                    $new = new Reading();
                    $new->meter_id = $readings[$i]->meter_id;
                    $new->billable = 0;
                    $new->reading = $readings[$i]->reading;
                    // if ($meter->unit_measurement == 0) {
                    //     $new->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
                    // } else {
                    //     $new->current_consumption = $current_consumption;
                    // }
                    $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
                    $new->current_consumption = $consumption;

                    $new->accepted1 = 0;
                    $new->accepted2 = 0;
                    $new->image = $readings[$i]->image;
                    $new->issue_code_id = $readings[$i]->issue_code_id;
                    $new->obstacle_code_id = $readings[$i]->obstacle_code_id;
                    $new->daily_average_consumption = $meter->daily_average_consumption;
                    $new->replacement_consumption = $meter->replacement_consumption;
                    $new->disconnection_reading = $meter->disconnection_reading;
                    $new->disconnection_date = $meter->disconnection_date;
                    $new->meter_reading_sequence = $meter->meter_reading_sequence;
                    $new->temperature = $meter->temperature;
                    $new->pressure = $meter->pressure;
                    $new->adjustment_consumption = $meter->adjustment_consumption;
                    $new->meter_reader_id = $request->meter_reader_id;
                    $new->supervisor_branch_id = 0;
                    $new->supervisor_id = 0;
                    $new->timestamps = false;
                    $bool = $new->save();
                    $date_check = date('Y-m-d H:i:s');
                    if (date('d') < 22) {
                        $date_check = date('Y-m-d H:i:s', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m-d H:i:s');
                    }
                    $new->created_at = $date_check;
                    $bool = $new->save();

                    if ($bool) {
                        if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                            $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                            $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                            $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                            if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                                $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                                $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                                $highlowPercent = ConsumptionVariant::find($meter->consumer_id);
                                if ($actualConsVar < $highlowPercent->low) {

                                    $new = new Notification();
                                    $new->title = 'Reading Too Low!';
                                    $new->content = $meter->meter_number;
                                    $new->meter_number = $meter->meter_number;
                                    $new->meter_id = $meter->id;
                                    $new->updated_by = $request->meter_reader_id;
                                    $new->save();

                                } elseif ($actualConsVar > $highlowPercent->high) {
                                    $new = new Notification();
                                    $new->title = 'Reading Too High!';
                                    $new->content = $meter->meter_number;
                                    $new->meter_number = $meter->meter_number;
                                    $new->meter_id = $meter->id;
                                    $new->updated_by = $request->meter_reader_id;
                                    $new->save();
                                }

                            }
                        }
                        // return response()->json([
                        //     'success' => true,
                        //     'data' => $reading,
                        // ]);
                        $counter++;
                    } else {
                        // return response()->json([
                        // 'success' => false,
                        // 'data' => $validator->messages()->first(),
                        // ]);
                    }
                }
            }
        }

        if (count($bills) > 0) {
            // $counterBill = 0;
            for ($i = 0; $i < count($bills); $i++) {
                // print($readings[$i]->meter_id);
                $date_check = date('Y-m');
                if (date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $existing_bill = Bill::where('consumer_id', $bills[$i]->consumer_id)->where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();
                if ($existing_bill) {
                    $existing_bill->consumer_id = $bills[$i]->consumer_id;
                    $existing_bill->bill_number = $bills[$i]->bill_number;
                    $existing_bill->deposit_amount = $bills[$i]->deposit_amount;
                    $existing_bill->bill_type = $bills[$i]->bill_type;
                    $existing_bill->bill_note_id = $bills[$i]->bill_note_id;
                    $existing_bill->bill_details = $bills[$i]->bill_details;
                    $existing_bill->meter_details = $bills[$i]->meter_details;
                    $existing_bill->charge_details = $bills[$i]->charge_details;
                    $existing_bill->bill_date = $bills[$i]->bill_date;
                    $existing_bill->reading_type = $bills[$i]->reading_type;
                    $existing_bill->current_bill = $bills[$i]->current_bill;
                    $existing_bill->adjustment = $bills[$i]->adjustment;
                    $existing_bill->other_charges = $bills[$i]->other_charges;
                    $existing_bill->current_total = $bills[$i]->current_total;
                    $existing_bill->arrears = $bills[$i]->arrears;
                    $existing_bill->bill_amount = $bills[$i]->bill_amount;
                    $existing_bill->bill_code = $bills[$i]->bill_code;
                    $existing_bill->last_reading_date = $bills[$i]->last_reading_date;
                    $existing_bill->current_reading_date = $bills[$i]->current_reading_date;
                    $existing_bill->payment_due_date = $bills[$i]->payment_due_date;
                    $existing_bill->meter_reader_id = $bills[$i]->meter_reader_id;
                    $existing_bill->bill_status = $bills[$i]->bill_status;
                    // $existing_bill->created_at = $bills[$i]->created_at;
                    $bool = $existing_bill->save();

                    if ($bills[$i]->bill_status == 0) {
                        if ($bool) {
                            // return response()->json([
                            //     'success' => true,
                            //     'data' => $reading,
                            // ]);
                            $counterBill++;
                        }
                    } else {
                        // return response()->json([
                        // 'success' => false,
                        // 'data' => $validator->messages()->first(),
                        // ]);
                    }
                } else {
                    $new = new Bill();
                    $new->consumer_id = $bills[$i]->consumer_id;
                    $new->bill_number = $bills[$i]->bill_number;
                    $new->deposit_amount = $bills[$i]->deposit_amount;
                    $new->bill_type = $bills[$i]->bill_type;
                    $new->bill_note_id = $bills[$i]->bill_note_id;
                    $new->bill_details = $bills[$i]->bill_details;
                    $new->meter_details = $bills[$i]->meter_details;
                    $new->charge_details = $bills[$i]->charge_details;
                    $new->bill_date = $bills[$i]->bill_date;
                    $new->reading_type = $bills[$i]->reading_type;
                    $new->current_bill = $bills[$i]->current_bill;
                    $new->adjustment = $bills[$i]->adjustment;
                    $new->other_charges = $bills[$i]->other_charges;
                    $new->current_total = $bills[$i]->current_total;
                    $new->arrears = $bills[$i]->arrears;
                    $new->bill_amount = $bills[$i]->bill_amount;
                    $new->bill_code = $bills[$i]->bill_code;
                    $new->last_reading_date = $bills[$i]->last_reading_date;
                    $new->current_reading_date = $bills[$i]->current_reading_date;
                    $new->payment_due_date = $bills[$i]->payment_due_date;
                    $new->meter_reader_id = $bills[$i]->meter_reader_id;
                    $new->bill_status = $bills[$i]->bill_status;
                    $new->created_at = $bills[$i]->created_at;
                    $bool = $new->save();

                    if ($bills[$i]->bill_status == 0) {
                        if ($bool) {
                            // return response()->json([
                            //     'success' => true,
                            //     'data' => $reading,
                            // ]);
                            $counterBill++;
                        }
                    } else {
                        // return response()->json([
                        // 'success' => false,
                        // 'data' => $validator->messages()->first(),
                        // ]);
                    }
                }
            }
        }

        $user = User::find($request->meter_reader_id);
        if ($user) {
            $handheld = Handheld::where('uuid', $user->uuid)->first();
            if ($handheld) {
                //log
                $new = new Log();
                $new->user = $request->meter_reader_id;
                $new->activity = "Send Data from Handheld ID: " . $handheld->id;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();
            }
        }

        return ['success' => true, 'data' => 'Saved!', 'total_passed_readings' => count($readings), 'total_saved_readings' => $counter, 'total_passed_bills' => count($bills), 'total_saved_bills' => $counterBill];
    }

    public function syncSingle(Request $request)
    {
        //klao x de, insert, kalo ada, tak payah.

        $meter = Meter::find($request->meter_id);
        if (!$meter) {
            return response()->json([
                'success' => false,
                'data' => 'Meter not found : id' . $request->meter_id,
            ]);
        }
        $max_dial_string = '';
        $max_dial_int = 0;
        for ($j = 0; $j < $meter->dial_length; $j++) {
            $max_dial_string .= '9';
        }
        if ($max_dial_string != '') {
            $max_dial_int = intval($max_dial_string);
        }
        $current_consumption = 0;
        if ($request->reading < $meter->last_reading) {
            $current_consumption = $request->reading + ($max_dial_int - $meter->last_reading);
        } else {
            $current_consumption = $request->reading - $meter->last_reading;
        }

        $date_check = date('Y-m');
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }
        $reading = Reading::where('meter_id', $request->meter_id)->where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();
        //dd($reading);
        if ($reading) {
            $reading->reading = $request->reading; //
            $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
            $reading->current_consumption = $consumption;
            // if ($meter->unit_measurement == 0) {
            //     $reading->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
            // } else {
            //     $reading->current_consumption = $current_consumption;
            // }

            //dd($reading->current_consumption);
            
            $reading->image = $request->image; //
            $reading->issue_code_id = $request->issue_code_id; //
            $reading->obstacle_code_id = $request->obstacle_code_id; //
            $reading->latitude = $request->latitude; //
            $reading->longitude = $request->longitude; //
            $bool = $reading->save();

            if ($bool) {

                if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                    $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                    if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                        $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                        $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                        $highlowPercent = ConsumptionVariant::find($meter->consumer_id);
                        if ($actualConsVar < $highlowPercent->low) {

                            $new = new Notification();
                            $new->title = 'Reading Too Low!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $reading->meter_reader_id;
                            $new->save();

                        } elseif ($actualConsVar > $highlowPercent->high) {
                            $new = new Notification();
                            $new->title = 'Reading Too High!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $reading->meter_reader_id;
                            $new->save();
                        }

                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $reading,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => $validator->messages()->first(),
                ]);
            }
        } else {
            $new = new Reading();
            $new->meter_id = $meter->id;
            $new->billable = 0;
            $new->reading = $request->reading;

            $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
            $reading->current_consumption = $consumption;
            // if ($meter->unit_measurement == 0) {
            //     $new->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
            // } else {
            //     $new->current_consumption = $current_consumption;
            // }
            $new->accepted1 = 0;
            $new->accepted2 = 0;
            $new->image = $request->image; //
            $new->issue_code_id = $request->issue_code_id; //
            $new->obstacle_code_id = $request->obstacle_code_id; //
            $new->daily_average_consumption = $meter->daily_average_consumption;
            $new->replacement_consumption = $meter->replacement_consumption;
            $new->disconnection_reading = $meter->disconnection_reading;
            $new->disconnection_date = $meter->disconnection_date;
            $new->meter_reading_sequence = $meter->meter_reading_sequence;
            $new->temperature = $meter->temperature;
            $new->pressure = $meter->pressure;
            $new->adjustment_consumption = $meter->adjustment_consumption;
            $new->meter_reader_id = $request->meter_reader_id;
            $new->supervisor_branch_id = 0;
            $new->supervisor_id = 0;
            $new->latitude = $request->latitude; //
            $new->longitude = $request->longitude; //
            $new->timestamps = false;
            $bool = $new->save();
            $date_check = date('Y-m-d H:i:s');
            if (date('d') < 22) {
                $date_check = date('Y-m-d H:i:s', strtotime('-28 days'));
            } else {
                $date_check = date('Y-m-d H:i:s');
            }
            $new->created_at = $date_check;
            $bool = $new->save();

            if ($bool) {
                if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                    $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                    if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                        $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                        $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                        $highlowPercent = ConsumptionVariant::find($meter->consumer_id);
                        if ($actualConsVar < $highlowPercent->low) {

                            $new = new Notification();
                            $new->title = 'Reading Too Low!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $request->meter_reader_id;
                            $new->save();

                        } elseif ($actualConsVar > $highlowPercent->high) {
                            $new = new Notification();
                            $new->title = 'Reading Too High!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $request->meter_reader_id;
                            $new->save();
                        }
                    }
                }
                return response()->json([
                    'success' => true,
                    'data' => $new,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => $validator->messages()->first(),
                ]);
            }
        }

    }
    public function updateReadingSingle(Request $request)
    {
        // update, bukan add
        //check meter ID, then check current month, if ada, update. kalo x de, return error. data not found or added.
        $meter = Meter::find($request->meter_id);
        $date_check = date('Y-m');
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }
        $reading = Reading::where('meter_id', $request->meter_id)->where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();
        if ($reading) {

            $max_dial_string = '';
            $max_dial_int = 0;
            for ($j = 0; $j < $meter->dial_length; $j++) {
                $max_dial_string .= '9';
            }
            if ($max_dial_string != '') {
                $max_dial_int = intval($max_dial_string);
            }
            $current_consumption = 0;
            if ($request->reading < $meter->last_reading) {
                $current_consumption = $request->reading + ($max_dial_int - $meter->last_reading);
            } else {
                $current_consumption = $request->reading - $meter->last_reading;
            }

            $reading->meter_id = $meter->id;
            $reading->billable = 0;
            $reading->reading = $request->reading;

            $consumption = ($current_consumption * $meter->corrected_volume)/$meter->z_factor;
            $reading->current_consumption = $consumption;

            // if ($meter->unit_measurement == 0) {
            //     $reading->current_consumption = $current_consumption * ($meter->cf_factor / 10000);
            // } else {
            //     $reading->current_consumption = $current_consumption;
            // }
            $reading->accepted1 = 0;
            $reading->accepted2 = 0;
            $reading->image = $request->image;
            $reading->issue_code_id = $request->issue_code_id;
            $reading->obstacle_code_id = $request->obstacle_code_id;
            $reading->daily_average_consumption = $meter->daily_average_consumption;
            $reading->replacement_consumption = $meter->replacement_consumption;
            $reading->disconnection_reading = $meter->disconnection_reading;
            $reading->disconnection_date = $meter->disconnection_date;
            $reading->meter_reading_sequence = $meter->meter_reading_sequence;
            $reading->temperature = $meter->temperature;
            $reading->pressure = $meter->pressure;
            $reading->adjustment_consumption = $meter->adjustment_consumption;
            $reading->meter_reader_id = $request->meter_reader_id;
            $reading->supervisor_branch_id = 0;
            $reading->supervisor_id = 0;
            $bool = $reading->save();

            if ($bool) {
                //log
                $new = new Log();
                $new->user = $request->meter_reader_id;
                $new->activity = "Updated Reading for #Meter: " . $meter->meter_number;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();

                if ($meter->meter_type == 0 || $meter->meter_type == '0' || $meter->meter_type == 2 || $meter->meter_type == '2') {
                    $one = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $two = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();
                    $three = Reading::where('meter_id', $meter->meter_id)->Where('created_at', 'like', '%' . date('Y-m', strtotime("-1 month")) . '%')->first();

                    if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                        $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                        $actualConsVar = ($reading - $actualAverage) / $actualAverage * 100;

                        $highlowPercent = ConsumptionVariant::find($meter->consumer_id);
                        if ($actualConsVar < $highlowPercent->low) {

                            $new = new Notification();
                            $new->title = 'Reading Too Low!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $request->meter_reader_id;
                            $new->save();

                        } elseif ($actualConsVar > $highlowPercent->high) {
                            $new = new Notification();
                            $new->title = 'Reading Too High!';
                            $new->content = $meter->meter_number;
                            $new->meter_number = $meter->meter_number;
                            $new->meter_id = $meter->id;
                            $new->updated_by = $request->meter_reader_id;
                            $new->save();
                        }

                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $reading,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => $validator->messages()->first(),
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'data' => 'Reading not exist',
            ]);
        }
    }

    public function checkRejectedReading(Request $request)
    {
        // $data = Reading::where('accepted1',1)->Where('created_at','like', '%'.date('Y-m').'%')->get();
        // $data2 = Reading::where('accepted2',1)->Where('created_at','like', '%'.date('Y-m').'%')->get();
        // $data3 = Reading::where('accepted1',1)->where('accepted2',1)->Where('created_at','like', '%'.date('Y-m').'%')->get();

        if ($request->route_id != null && $request->route_id != '') {
            $date_check = date('Y-m');
            if (date('d') < 22) {
                $date_check = date('Y-m', strtotime('-28 days'));
            } else {
                $date_check = date('Y-m');
            }
            $route_id = explode(',', $request->route_id);
            // dd($request->route_id);
            // dd($route_id);
            // dd([1,4]);
            $data = \DB::table('readings')->select('readings.*')->leftjoin('meters', 'readings.meter_id', '=', 'meters.id')
                ->leftjoin('consumers', 'meters.consumer_id', '=', 'consumers.id')
                ->join('routes', function ($join) use ($route_id) {
                    $join->on('routes.id', '=', 'consumers.route_id')
                        ->whereIn('routes.id', $route_id);
                })
            // ->leftjoin('routes','routes.id' , '=' , 'consumers.route_id')
            // ->whereIn('routes.id' , [1,4])
                ->where('readings.created_at', 'like', '%' . $date_check . '%')
                ->where('readings.accepted1', 1)
                ->orWhere('readings.accepted2', 1)
                ->get();
            // dd($data);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Route id(s) is required',
            ]);
        }

        // $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->select('routes.*','handhelds.uuid');

        // $data = Reading::leftjoin('meters', 'readings.meter_id', '=', 'meters.id')
        // ->leftjoin('consumers', 'meters.consumer_id' , '=', 'consumers.id')
        // ->leftjoin('routes','routes.id' , '=' , 'consumers.route_id')
        // ->WhereIn('routes.id' , array($route_id))
        // ->Where('readings.accepted1',1)
        // ->Where('readings.created_at','like', '%'.date('Y-m').'%')
        // ->get();

        // $data2 = Reading::leftjoin('meters', 'readings.meter_id', '=', 'meters.id')
        // ->leftjoin('consumers', 'meters.consumer_id' , '=', 'consumers.id')
        // ->leftjoin('routes','routes.id' , '=' , 'consumers.route_id')
        // ->WhereIn('routes.id' , array($route_id))
        // ->Where('readings.accepted2',1)
        // ->Where('readings.created_at','like', '%'.date('Y-m').'%')
        // ->get();

        // $data3 = Reading::leftjoin('meters', 'readings.meter_id', '=', 'meters.id')
        // ->leftjoin('consumers', 'meters.consumer_id' , '=', 'consumers.id')
        // ->leftjoin('routes','routes.id' , '=' , 'consumers.route_id')
        // ->WhereIn('routes.id' , array($route_id))
        // ->Where('readings.accepted1',1)
        // ->Where('accepted2',1)
        // ->Where('readings.created_at','like', '%'.date('Y-m').'%')
        // ->get();

        // dd($data);

        // $request->route_id;

        // $collection = $data->concat($data2)->concat($data3);

        // return response()->json([
        //                 'success' => true,
        //                 'data' => $collection,
        //             ]);
    }

    public function logout(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'reading' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'data' => $validator->messages()->first(),
        //     ]);
        // }

        // $readings = json_decode($request->reading);

        // $counter = 0;
        // for ($i=0; $i < count($readings) ; $i++) {
        //     // print($readings[$i]->meter_id);
        //     $date_check = date('Y-m');
        //     if(date('d') < 22) {
        //         $date_check = date('Y-m', strtotime('-28 days'));
        //     } else {
        //         $date_check = date('Y-m');
        //     }
        //     $existing_reading = Reading::where('meter_id',$readings[$i]->meter_id)->where('created_at','like', '%'.$date_check.'%')->orderBy('created_at','desc')->first();
        //     if($existing_reading) {
        //         $existing_reading->reading = $readings[$i]->reading; //
        //         $existing_reading->image = $readings[$i]->image; //
        //         $existing_reading->issue_code = $readings[$i]->issue_code;//
        //         $existing_reading->obstacle_code = $readings[$i]->obstacle_code;//
        //         $existing_reading->latitude = $readings[$i]->latitude; //
        //         $existing_reading->longitude = $readings[$i]->longitude; //
        //         $bool = $existing_reading->save();

        //         if ($bool) {
        //             $counter++;
        //         }
        //         else
        //         {
        //             //
        //         }
        //     } else {
        //         $meter = Meter::find($readings[$i]->meter_id);
        //         $new = new Reading();
        //         $new->meter_id = $readings[$i]->meter_id;
        //         $new->billable = 0;
        //         $new->reading = $readings[$i]->reading;
        //         $new->accepted1 = 0;
        //         $new->accepted2 = 0;
        //         $new->image = $readings[$i]->image;
        //         $new->issue_code = $readings[$i]->issue_code;
        //         $new->obstacle_code = $readings[$i]->obstacle_code;
        //         $new->daily_average_consumption = $meter->daily_average_consumption;
        //         $new->replacement_consumption = $meter->replacement_consumption;
        //         $new->disconnection_reading = $meter->disconnection_reading;
        //         $new->disconnection_date = $meter->disconnection_date;
        //         $new->meter_reading_sequence = $meter->meter_reading_sequence;
        //         $new->temperature = $meter->temperature;
        //         $new->pressure = $meter->pressure;
        //         $new->adjustment_consumption = $meter->adjustment_consumption;
        //         $new->meter_reader_id = $request->meter_reader_id;
        //         $new->supervisor_branch_id = 0;
        //         $new->supervisor_id = 0;
        //         $bool = $new->save();

        //         if ($bool) {
        //             $counter++;
        //         }
        //         else
        //         {
        //             //
        //         }
        //     }
        // }
        if ($request->bookmark_ids != null && $request->bookmark_ids != '') {
            $bookmark_ids = explode(',', $request->bookmark_ids);
            Consumer::whereIn('id', $bookmark_ids)->update(array('bookmark' => 1));
        }

        $user = User::find($request->meter_reader_id);

        if ($user) {
            $user->remember_token = null;
            $user->uuid = null;

            $user->save();

            //log
            $new = new Log();
            $new->user = $user->id;
            $new->activity = "User Logout from Handheld";
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
        }

        // return ['success' => true, 'data'=>'Saved!', 'total_passed_readings' => count($readings), 'total_saved_readings' => $counter];
        return ['success' => true, 'data' => 'User successfully logout'];
    }

    public function broadcastAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meter_reader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }
        $broadcast = Broadcast::whereIn('send_to', [0, $request->meter_reader_id])->orderByDesc('created_at')->get();
        return response()->json([
            'success' => true,
            'data' => $broadcast,
        ]);
    }
    public function broadcastLatest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meter_reader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $broadcast = Broadcast::latest('created_at')->whereIn('send_to', [0, $request->meter_reader_id])->first();

        return response()->json([
            'success' => true,
            'data' => $broadcast,
        ]);
    }

    public function resetPassword()
    {
        $credentials = request()->validate(['email' => 'required|email']);

        $user = User::Where('email', $credentials['email'])->first();

        if ($user) {
            Password::sendResetLink($credentials);
            return response()->json(['success' => true, 'data' => 'Reset password link sent to your email.']);
        } else {
            return response()->json(['success' => false, 'data' => 'Email not registered!']);
        }

    }

    public function savePlayerId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required',
            'player_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $handheld = Handheld::Where('uuid', $request->uuid)->first();

        if ($handheld) {
            $handheld->player_id = $request->player_id;
            $handheld->save();
            return response()->json(['success' => true, 'data' => 'Saved!']);
        } else {
            return response()->json(['success' => false, 'data' => 'Handheld not found!']);
        }
    }

    public function getNewAssignedRoute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required',
            'route_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $handheld = Handheld::where('uuid', $request->uuid)->first();
        $user = User::where('uuid', $request->uuid)->first();

        if ($handheld) {

            if ($request->route_id != null && $request->route_id != '') {
                $route_id = explode(',', $request->route_id);
                $date_check = date('Y-m');
                if (date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $handheldRoute = HandheldRoute::select('route_id')->distinct('route_id')->where('created_at', 'like', '%' . $date_check . '%')->where('handheld_id', $handheld->id)->whereNotIn('route_id', $route_id)->pluck('route_id');

                if (count($handheldRoute) > 0) {
                    $route = Route::whereIn('id', $handheldRoute)->get();
                    $consumer = Consumer::whereIn('route_id', $handheldRoute)->get();
                    // $issueCode = IssueCode::all();
                    // $obstacleCode = ObstacleCode::all();
                    // $meterLocation = MeterLocation::all();

                    $subRoute = array();
                    for ($i = 0; $i < count($route); $i++) {
                        if ($route[$i]->combine != null || $route[$i]->combine != '') {

                            $ids = explode(',', $route[$i]->combine);
                            $consumerIds = Consumer::whereIn('route_id', $ids)->get();
                            $consumer = $consumer->merge($consumerIds);

                            for ($j = 0; $j < count($ids); $j++) {

                                $combineRoute = Route::find($ids[$j]);

                                $subRoute[$j] = $combineRoute;
                            }
                        }
                    }

                    foreach ($consumer as $c) {
                        $meter = Meter::where('consumer_id', $c->id)->get();

                        for ($i = 0; $i < count($meter); $i++) {
                            $date_check = date('Y-m');
                            if (date('d') < 22) {
                                $date_check = date('Y-m', strtotime('-28 days'));
                            } else {
                                $date_check = date('Y-m');
                            }
                            $reading = Reading::Where('meter_id', $meter[$i]->id)->Where('created_at', 'like', '%' . $date_check . '%')->first();
                            $meter[$i]->reading = $reading;
                        }

                        $c->meter = $meter;

                        $date_check_2 = date('Y-m', strtotime('-28 days'));
                        if (date('d') < 22) {
                            $date_check_2 = date('Y-m', strtotime('-56 days'));
                        } else {
                            $date_check_2 = date('Y-m', strtotime('-28 days'));
                        }
                        $adjustment_bill = Bill::Where('consumer_id', $c->id)->Where('created_at', 'like', '%' . $date_check_2 . '%')->first();
                        $c->adjustment_bill = $adjustment_bill;
                    }

                    $consumerIdsForCurrentRoute = Consumer::whereIn('route_id', $handheldRoute)->pluck('id');
                    $bill = Bill::whereIn('consumer_id', $consumerIdsForCurrentRoute)->where('created_at', 'like', '%' . $date_check . '%')->get();

                    if ($route) {
                        //log
                        $new = new Log();
                        $new->user = $user->id;
                        $new->activity = "Load Newly Assigned Route Data at Handheld ID: " . $handheld->id;
                        $new->month = Date('m');
                        $new->year = Date('Y');
                        $new->save();
                    }

                    return response()->json([
                        'success' => true,
                        'consumer' => $consumer,
                        // 'obstacleCode' => $obstacleCode,
                        // 'issueCode' => $issueCode,
                        // 'meterLocation' => $meterLocation,
                        'route' => $route,
                        'subRoute' => $subRoute,
                        'bill' => $bill,
                        // 'reading' => $reading,
                    ]);
                } else {
                    // $new = new Log();
                    // $new->user = $user->id;
                    // $new->activity = "Route id is empty";
                    // $new->month = Date('m');
                    // $new->year = Date('Y');
                    // $new->save();
                    return response()->json([
                        'success' => false,
                        'data' => 'Route id is empty',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => 'No new assigned route',
                ]);
            }
        } else {
            $new = new Log();
            $new->user = $user->id;
            $new->activity = "Handheld not found";
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            return response()->json([
                'success' => false,
                'data' => 'Handheld Not found',
            ]);
        }
    }

    public function getUpdatedMeterInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consumer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $meter = Meter::where('consumer_id', $request->consumer_id)->get();

        if ($meter) {

            return response()->json([
                'success' => true,
                'data' => $meter,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'data' => 'Meter Not Found',
            ]);
        }

    }

    public function saveBill(Request $request)
    {

        $new = new Bill();
        $new->consumer_id = $request->consumer_id;
        $new->bill_number = $request->bill_number;
        $new->deposit_amount = $request->deposit_amount;
        $new->bill_type = $request->bill_type;
        $new->bill_note_id = $request->bill_note_id;
        $new->bill_details = $request->bill_details;
        $new->meter_details = $request->meter_details;
        $new->charge_details = $request->charge_details;
        $new->bill_date = $request->bill_date;
        $new->reading_type = $request->reading_type;
        $new->current_consumption = $request->current_consumption;
        $new->current_bill = $request->current_bill;
        $new->adjustment = $request->adjustment;
        $new->other_charges = $request->other_charges;
        $new->current_total = $request->current_total;
        $new->arrears = $request->arrears;
        $new->bill_amount = $request->bill_amount;
        $new->bill_code = $request->bill_code;
        $new->last_reading_date = $request->last_reading_date;
        $new->current_reading_date = $request->current_reading_date;
        $new->payment_due_date = $request->payment_due_date;
        $new->meter_reader_id = $request->meter_reader_id;
        $new->created_at = $request->created_at;
        $bool = $new->save();

        if ($bool) {

            return response()->json([
                'success' => true,
                'data' => $new,
            ]);
        }

    }

    public function updateBill(Request $request)
    {

        $consumer_id = $request->consumer_id;

        $date_check = date('Y-m');
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        $bill = Bill::Where('consumer_id', $consumer_id)->Where('created_at', 'like', '%' . $date_check . '%')->first();

        if ($bill) {
            $bill->consumer_id = $request->consumer_id;
            $bill->bill_number = $request->bill_number;
            $bill->deposit_amount = $request->deposit_amount;
            $bill->bill_type = $request->bill_type;
            $bill->bill_note_id = $request->bill_note_id;
            $bill->bill_details = $request->bill_details;
            $bill->meter_details = $request->meter_details;
            $bill->charge_details = $request->charge_details;
            $bill->bill_date = $request->bill_date;
            $bill->reading_type = $request->reading_type;
            $bill->current_consumption = $request->current_consumption;
            $bill->current_bill = $request->current_bill;
            $bill->adjustment = $request->adjustment;
            $bill->other_charges = $request->other_charges;
            $bill->current_total = $request->current_total;
            $bill->arrears = $request->arrears;
            $bill->bill_amount = $request->bill_amount;
            $bill->bill_code = $request->bill_code;
            $bill->last_reading_date = $request->last_reading_date;
            $bill->current_reading_date = $request->current_reading_date;
            $bill->payment_due_date = $request->payment_due_date;
            $bill->meter_reader_id = $request->meter_reader_id;
            $bill->bill_status = 0;
            // $bill->created_at = $request->created_at;
            $bool = $bill->save();

            if ($bool) {
                return response()->json([
                    'success' => true,
                    'data' => $bill,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => 'Fail to update bill',
                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'data' => 'Bill not found',
            ]);
        }
    }

    public function syncBillSingle(Request $request)
    {

        $consumer_id = $request->consumer_id;

        $date_check = date('Y-m');
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        $bill = Bill::Where('consumer_id', $consumer_id)->Where('created_at', 'like', '%' . $date_check . '%')->first();

        if ($bill) {
            $bill->consumer_id = $request->consumer_id;
            $bill->bill_number = $request->bill_number;
            $bill->deposit_amount = $request->deposit_amount;
            $bill->bill_type = $request->bill_type;
            $bill->bill_note_id = $request->bill_note_id;
            $bill->bill_details = $request->bill_details;
            $bill->meter_details = $request->meter_details;
            $bill->charge_details = $request->charge_details;
            $bill->bill_date = $request->bill_date;
            $bill->reading_type = $request->reading_type;
            $bill->current_consumption = $request->current_consumption;
            $bill->current_bill = $request->current_bill;
            $bill->adjustment = $request->adjustment;
            $bill->other_charges = $request->other_charges;
            $bill->current_total = $request->current_total;
            $bill->arrears = $request->arrears;
            $bill->bill_amount = $request->bill_amount;
            $bill->bill_code = $request->bill_code;
            $bill->last_reading_date = $request->last_reading_date;
            $bill->current_reading_date = $request->current_reading_date;
            $bill->payment_due_date = $request->payment_due_date;
            $bill->meter_reader_id = $request->meter_reader_id;
            $bill->created_at = $request->created_at;
            $bool = $bill->save();

            if ($bool) {
                return response()->json([
                    'success' => true,
                    'data' => $new,
                ]);
            }
        } else {

            $new = new Bill();
            $new->consumer_id = $request->consumer_id;
            $new->bill_number = $request->bill_number;
            $new->deposit_amount = $request->deposit_amount;
            $new->bill_type = $request->bill_type;
            $new->bill_note_id = $request->bill_note_id;
            $new->bill_details = $request->bill_details;
            $new->meter_details = $request->meter_details;
            $new->charge_details = $request->charge_details;
            $new->bill_date = $request->bill_date;
            $new->reading_type = $request->reading_type;
            $new->current_consumption = $request->current_consumption;
            $new->current_bill = $request->current_bill;
            $new->adjustment = $request->adjustment;
            $new->other_charges = $request->other_charges;
            $new->current_total = $request->current_total;
            $new->arrears = $request->arrears;
            $new->bill_amount = $request->bill_amount;
            $new->bill_code = $request->bill_code;
            $new->last_reading_date = $request->last_reading_date;
            $new->current_reading_date = $request->current_reading_date;
            $new->payment_due_date = $request->payment_due_date;
            $new->meter_reader_id = $request->meter_reader_id;
            $new->created_at = $request->created_at;
            $bool = $new->save();

            if ($bool) {
                return response()->json([
                    'success' => true,
                    'data' => $new,
                ]);
            }
        }
    }

    public function syncBill(Request $request)
    {

        if ($request->consumer_ids != null && $request->consumer_ids != '') {
            $consumer_ids = explode(',', $request->consumer_ids);
            $passed_count_normal_bill = $request->count_normal_bill;
            $passed_count_cancelled_bill = $request->count_cancelled_bill;
            $bills = json_decode($request->bill);

            $date_check = date('Y-m');
            if (date('d') < 22) {
                $date_check = date('Y-m', strtotime('-28 days'));
            } else {
                $date_check = date('Y-m');
            }
            // $handheldRoute = HandheldRoute::select('route_id')->distinct('route_id')->where('created_at','like', '%'.$date_check.'%')->where('handheld_id',$handheld->id)->whereNotIn('route_id', $route_id)->pluck('route_id');

            if (count($consumer_ids) > 0) {
                $count_normal_bill = Bill::whereIn('consumer_id', $consumer_ids)->where('bill_status', 0)->where('created_at', 'like', '%' . $date_check . '%')->count();
                $count_cancelled_bill = Bill::whereIn('consumer_id', $consumer_ids)->where('bill_status', 1)->where('created_at', 'like', '%' . $date_check . '%')->count();

                if (($count_normal_bill != $passed_count_normal_bill) || ($count_cancelled_bill != $passed_count_cancelled_bill)) {
                    if (count($bills) > 0) {
                        for ($i = 0; $i < count($bills); $i++) {
                            // print($readings[$i]->meter_id);
                            $date_check = date('Y-m');
                            if (date('d') < 22) {
                                $date_check = date('Y-m', strtotime('-28 days'));
                            } else {
                                $date_check = date('Y-m');
                            }
                            $existing_bill = Bill::where('consumer_id', $bills[$i]->consumer_id)->where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();
                            if ($existing_bill) {
                                $existing_bill->consumer_id = $bills[$i]->consumer_id;
                                $existing_bill->bill_number = $bills[$i]->bill_number;
                                $existing_bill->deposit_amount = $bills[$i]->deposit_amount;
                                $existing_bill->bill_type = $bills[$i]->bill_type;
                                $existing_bill->bill_note_id = $bills[$i]->bill_note_id;
                                $existing_bill->bill_details = $bills[$i]->bill_details;
                                $existing_bill->meter_details = $bills[$i]->meter_details;
                                $existing_bill->charge_details = $bills[$i]->charge_details;
                                $existing_bill->bill_date = $bills[$i]->bill_date;
                                $existing_bill->reading_type = $bills[$i]->reading_type;
                                $existing_bill->current_bill = $bills[$i]->current_bill;
                                $existing_bill->adjustment = $bills[$i]->adjustment;
                                $existing_bill->other_charges = $bills[$i]->other_charges;
                                $existing_bill->current_total = $bills[$i]->current_total;
                                $existing_bill->arrears = $bills[$i]->arrears;
                                $existing_bill->bill_amount = $bills[$i]->bill_amount;
                                $existing_bill->bill_code = $bills[$i]->bill_code;
                                $existing_bill->last_reading_date = $bills[$i]->last_reading_date;
                                $existing_bill->current_reading_date = $bills[$i]->current_reading_date;
                                $existing_bill->payment_due_date = $bills[$i]->payment_due_date;
                                $existing_bill->meter_reader_id = $bills[$i]->meter_reader_id;
                                $existing_bill->bill_status = $bills[$i]->bill_status;
                                // $existing_bill->created_at = $bills[$i]->created_at;
                                $bool = $existing_bill->save();
                            } else {
                                $new = new Bill();
                                $new->consumer_id = $bills[$i]->consumer_id;
                                $new->bill_number = $bills[$i]->bill_number;
                                $new->deposit_amount = $bills[$i]->deposit_amount;
                                $new->bill_type = $bills[$i]->bill_type;
                                $new->bill_note_id = $bills[$i]->bill_note_id;
                                $new->bill_details = $bills[$i]->bill_details;
                                $new->meter_details = $bills[$i]->meter_details;
                                $new->charge_details = $bills[$i]->charge_details;
                                $new->bill_date = $bills[$i]->bill_date;
                                $new->reading_type = $bills[$i]->reading_type;
                                $new->current_bill = $bills[$i]->current_bill;
                                $new->adjustment = $bills[$i]->adjustment;
                                $new->other_charges = $bills[$i]->other_charges;
                                $new->current_total = $bills[$i]->current_total;
                                $new->arrears = $bills[$i]->arrears;
                                $new->bill_amount = $bills[$i]->bill_amount;
                                $new->bill_code = $bills[$i]->bill_code;
                                $new->last_reading_date = $bills[$i]->last_reading_date;
                                $new->current_reading_date = $bills[$i]->current_reading_date;
                                $new->payment_due_date = $bills[$i]->payment_due_date;
                                $new->meter_reader_id = $bills[$i]->meter_reader_id;
                                $new->bill_status = $bills[$i]->bill_status;
                                $new->created_at = $bills[$i]->created_at;
                                $bool = $new->save();
                            }
                        }

                        return response()->json([
                            'success' => true,
                            'data' => 'Bills synced successfully',
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'data' => 'Nothing to sync',
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'data' => 'Nothing to sync',
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => 'No customer ids',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No customer ids',
            ]);
        }

    }

    public function cancelBill(Request $request)
    {
        $consumer_id = $request->consumer_id;

        $date_check = date('Y-m');
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        $bill = Bill::where('consumer_id', $consumer_id)->Where('created_at', 'like', '%' . $date_check . '%')->orderBy('created_at', 'desc')->first();

        if ($bill) {
            $bill->bill_status = 1;
            $bill->save();

            return response()->json([
                'success' => true,
                'data' => 'Bill Cancelled!',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => 'Bill Not Found!',
            ]);
        }
    }

    public function cancelReading(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reading_id' => 'required',
            'meter_reader_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $reading_id = $request->reading_id;
        $reading = Reading::where('id', $request->reading_id)
            ->where('accepted1', 0)
            ->where('accepted2', 0)
            ->first();
        if ($reading) {
            $reading->delete();
            $new = new Log();
            $new->user = $request->meter_reader_id;
            $new->activity = "Reading cancelled [meter: " . $reading->meter->meter_number . " ,reading: " . $reading->reading . " ]";
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            return response()->json([
                'success' => true,
                'data' => 'Reading Cancelled!',
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => 'Reading Not Found or Already Approved/Rejected',
        ]);
    }

    public function noti()
    {
        if (date('d') < 22) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }
        return response()->json([
            'notifications' => Notification::Where('created_at', 'like', '%' . $date_check . '%')->get(),
            'count' => count(Notification::Where('created_at', 'like', '%' . $date_check . '%')->get()),
        ]);
    }
}
