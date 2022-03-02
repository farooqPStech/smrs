<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consumer;
use App\Models\Reading;
use App\Models\Branch;
use App\Models\Meter;
use App\Models\Route;
use App\Models\MeterLocation;
use App\Models\IssueCode;
use App\Models\ObstacleCode;
use App\Models\Log;


use App\Models\Price;
use App\Models\Highlow;
use App\Models\DueDate;
use App\Models\BillNote;
use App\Models\Printer;
use App\Models\CompanyInformations;
use App\Models\Notification;
use App\Models\RejectCode;
Use Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $date_check = date('Y-m');
        if(date('d') < 25) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        //dd($date_check);
        $totalMeter = Meter::select('id')->get();

        $month = date('M',strtotime("-1 month"));

        $unread = count($totalMeter) - 0;

        $faulty = Reading::select('id')->where('created_at','like','%'.$date_check.'%')->where('issue_code_id','02')->get();

        $noti = Notification::all();

        $reject_code = RejectCode::select('id','description')->get();

        $branches = Branch::select('id','name')->get();

        $arr = array();
        $arr2 = array();
        $name = array();
        for ($i=0; $i < count($reject_code); $i++) {
            array_push($name, $reject_code[$i]->id);
            array_push($arr2, $reject_code[$i]->description);
        }

        //$industrialMeters = Consumer::where('consumer_type', 11)->get();

        // $readings = Reading::where('created_at','like','%'.$date_check.'%')
        //     ->whereIn('meter_id', $industrialMeters)
        //     ->count();

        $arr = array_combine($name, $arr2);

        $items = array(

            'branches' => $branches,

            'totalMeter' => count($totalMeter),
            'totalReading' => 0,//count($readings),
            // 'readings' => $readings,

            'currentMonth' => $month,
            'unread' => $unread,
            'faulty' => count($faulty),
            'noti' => $noti,
            'reject_code' => $arr,

        );

        return view('home')->with($items);
    }

    public function viewTrend($meterId)
    {

        $readings = Reading::where('meter_id',$meterId)->get();

        $meter1 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 1)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter2 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 2)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter3 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 3)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter4 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 4)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter5 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 5)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter6 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 6)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter7 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 7)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter8 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 8)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter9 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 9)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter10 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 10)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter11 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 11)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();
        $meter12 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 12)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('current_consumption')
                        ->first();

        $meterDetail = Meter::where('id',$meterId)->first();

        $consumer = Consumer::Where('id',$meterDetail->consumer_id)->first();



        $items = array(
            'meter1' => $meter1,
            'meter2' => $meter2,
            'meter3' => $meter3,
            'meter4' => $meter4,
            'meter5' => $meter5,
            'meter6' => $meter6,
            'meter7' => $meter7,
            'meter8' => $meter8,
            'meter9' => $meter9,
            'meter10' => $meter10,
            'meter11' => $meter11,
            'meter12' => $meter12,
            'meterDetail' => $meterDetail,
            'consumer' => $consumer,
        );

        // dd($meter);

        return view('viewTrend')->with($items);
    }

    public function viewImage($id)
    {
         $reading = Reading::Where('id',$id)->first();
            alert()->html("<img style='height: 500px; width: 500px' src='data:image/gif;base64,".$reading->image."'>");
            return Redirect::back();
    }

    public function image($id)
    {
         $reading = Reading::Where('id',$id)->first();
            // alert()->html("<img style='height: 500px; width: 500px' src='data:image/gif;base64,".$reading->image."'>");
            return json_encode($reading->image);
    }


    public function verify($readingId,$userType,$userId){

        $data = Reading::find($readingId);

        if ($userType == 2) {
            $data->accepted2 = 2;
        }
        elseif ($userType = 3) {
            $data->accepted1 = 2;
        }

        $bool = $data->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Reading Verified At ".$data;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Reading Verified!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Fail to Update!');
            return Redirect::back();
        }

    }

    public function revoke($readingId,$userType,$userId){

        $data = Reading::find($readingId);

        if ($userType == 2) {
            $data->accepted2 = 0;
        }
        elseif ($userType = 3) {
            $data->accepted1 = 0;
        }

        $bool = $data->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Reading Revoked At ".$data;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Reading Revoked!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Fail to Update!');
            return Redirect::back();
        }

    }

    public function reject($readingId,$userType,$userId,$reason,$rejectCode)
    {
        $data = Reading::find($readingId);

        if ($userType == 2 || $userType == 5) {
            $data->accepted2 = 1;
            $data->reason2 = $reason;
            $data->reject_code2 = intval($rejectCode);
        }
        elseif ($userType == 3 || $userType == 6) {
            $data->accepted1 = 1;
            $data->reason1 = $reason;
            $data->reject_code1 = intval($rejectCode);
        }

        $bool = $data->save();


        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Reading Rejected At ".$data;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Reading Rejected!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Fail to Update!');
            return Redirect::back();
        }
    }

    public function reader(Request $request)
    {
         $a = $request->hasFile('fileToUpload');
        $image = $request->file('fileToUpload');
        // dd($image);
        // $content =  file_get_contents($image);

        $reads = file($image);
        foreach($reads as $read)
        {
            if(substr($read, 0, 1) == 1)
            {

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
                $bill_number = substr($read, 210, 8);
                $area_code = substr($read, 218, 11);
                $consumer_type = substr($read, 229, 2);
                $consumer_status = substr($read, 231, 2);
                $bill_type = substr($read, 233, 1);
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
                $consecutive_estimated_amount = substr($read, 344, 10);
                $capital_contribution = substr($read, 354, 10);
                $fix_charges = substr($read, 364, 10);
                $calorific_value = substr($read, 374, 8);
                $special_rate = substr($read, 382, 7);
                $telephone_number = substr($read, 389, 10);
                $market_price = substr($read, 399, 7);
                $customer_full_name = substr($read, 406, 65);


                $result1 = substr($read, 75, 40);
                $result2 = substr($read, 115, 40);
                $result3 = substr($read, 155, 40);
                echo '<br>'. $result1.' '.$result2.' '.$result3;
            }
            else if (substr($read, 0, 1) == 2)
            {
                $prefix = substr($read, 0,1);
                $route = substr($read, 1,6);
                $meter_number = substr($read, 7,25);
                $unit_measurement = substr($read, 32,1);
                $dial_length = substr($read, 33,1);
                $meter_location = substr($read, 34,2);
                $meter_type = substr($read, 36,1);
                $last_reading = substr($read, 37,9);
                $last_reading_date = substr($read, 46,8);
                $last_reading_status = substr($read, 54,2);
                $daily_average_consumption = substr($read, 56,9);
                $meter_installation_date = substr($read, 65,8);
                $replacement_consumption = substr($read, 73,9);
                $disconnection_reading = substr($read, 82,9);
                $disconnnection_date = substr($read, 91,8);
                $meter_reading_sequence = substr($read, 99,4);
                $temperature = substr($read, 103,5);
                $pressure = substr($read, 108,6);
                $cf_factor = substr($read, 114,7);
                $z_factor = substr($read, 121,5);
                $adjustment_consumption = substr($read, 126,10);
            }
        }

        //download file structure (Industrial)







        //download file structure (GAS RC)

        // $prefix = substr($read, 0,1);
        // $route = substr($read, 0,6);
        // $consumer_number = substr($read,7, 11)
        // $old_account_number = substr($read, 18,14);
        // $tariff_code = substr($read, 32 , 2);
        // $consumer_name = substr($read, 34 , 50);
        // $consumer_address_1 = substr($read, 84, 60);
        // $consumer_address_2 = substr($read, 144, 60);
        // $consumer_address_3 = substr($read, 204, 60);
        // $postal_code = substr($read, 264, 5);
        // $contract_number = substr($read, 269, 15);
        // $bill_number = substr($read, 284, 8);
        // $consumer_type = substr($read, 292, 2);
        // $area_code = substr($read, 294, 9);
        // $supply_type = substr($read, 303, 2);
        // $consumer_status = substr($read, 305, 2);
        // $bill_type = substr($read, 307, 1);
        // $arrears = substr($read, 308, 11);
        // $deposit_type = substr($read, 319, 2);
        // $deposit = substr($read, 321, 10);
        // $last_normal_reading_date = substr($read, 331, 8);
        // $last_bill_number = substr($read, 339, 8);
        // $last_bill_date = substr($read, 347, 8);
        // $last_bill_amount = substr($read, 355, 10);
        // $last_payment_number = substr($read, 365, 8);
        // $last_payment_date = substr($read, 373, 8);
        // $last_payment_amount = substr($read, 381, 10);
        // $last_bill_code = substr($read, 391, 1);
        // $download_date = substr($read, 392, 8);
        // $postal_address_1 = substr($read, 400, 60);
        // $postal_address_2 = substr($read, 460, 60);
        // $postal_address_3 = substr($read, 520, 60);
        // $postal_address_post_code = substr($read, 580, 5);
        // $evc = substr($read, 585, 1);
        // $interest_charges = substr($read, 586, 10);
        // $rebate = substr($read, 596, 10);
        // $spi = substr($read, 606, 5);
        // $consecutive_estimate_count = substr($read, 611, 2);
        // $consecutive_estimated_amount = substr($read, 613, 10);
        // $capital_contribution = substr($read, 623, 10);
        // $fix_charges = substr($read, 633, 10);
        // $calorific_value = substr($read, 643, 10);
        // $special_rate = substr($read, 653, 7);
        // $telephone_number = substr($read, 660, 10);
        // $provision_rate = substr($read, 670, 7);
        // $installation_amount = substr($read, 677, 11);
        // $inspection_amount = substr($read, 688, 11);
        // $reconnection_amount = substr($read, 699, 11);
        // $additional_deposit = substr($read, 710, 11);
        // $gst_relief_indicator = substr($read,721, 1);
        // $gst_register_indicator = substr($read, 722, 1);
        // $ng_lpg_company_name = substr($read, 723, 60);
        // $ng_lpg_company_address = substr($read, 783, 60);
        // $ng_lpg_company_registration_no = substr($read, 843, 30);
        // $ng_lpg_gst_number = substr($read, 873, 30);
        // $ng_lpg_telephone_number = substr($read, 903, 30);
        // $ng_lpg_fax_number = substr($read, 933, 30);
        // $consecutive_gst_amount = substr($read, 963, 10);
        // $gst_hq_name = substr($read, 973, 55);
        // $retail_license_name = substr($read, 1028, 50);
        // $landed_property_indicator = substr($read, 1078, 1);


        // $prefix = substr($read, 0, 1);
        // $route = substr($read, 1, 6);
        // $meter_number = substr($read, 7, 15);
        // $meter_sequence_number = substr($read, 22, 10);
        // $unit_measurement = substr($read, 32, 1);
        // $dial_length = substr($read, 33, 1);
        // $meter_location = substr($read, 34, 2);
        // $last_reading = substr($read, 36, 9);
        // $last_reading_date = substr($read, 45, 8);
        // $last_reading_status = substr($read, 53, 2);
        // $daily_average_consumption = substr($read, 55, 9);
        // $meter_installation_date = substr($read, 64, 8);
        // $replacement_consumption = substr($read, 72, 9);
        // $control_reading = substr($read, 81, 9);
        // $control_reading_date = substr($read, 90, 8);
        // $meter_status = substr($read, 98, 2);
        // $meter_reading_sequence = substr($read, 100, 4);
        // $temperature = substr($read, 104, 10);
        // $pressure = substr($read, 114, 7);
        // $cf_factor = substr($read, 121, 7);


    }


    public function uploadToJDE()
    {

        $test = Consumer::find(1);
        $file = "abctest.txt";
        $txt = fopen($file, "w") or die("Unable to open file!");
        $content = $test->consumer_address_1.$test->consumer_address_2.$test->consumer_address_3;
        fwrite($txt, $content.$content);
        fclose($txt);

        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    public function alert()
    {
        //type 2,5, superuser
        //type 3,6, supervisor

        if (auth()->user()->branch_id == 2 || auth()->user()->branch_id == 5 || auth()->user()->branch_id == 1) {
            $industrialConsumers = Consumer::where('consumer_type','11')->pluck('id');
            $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');
            $industrialNoti = Notification::whereIn('meter_id',$industrialMeters)->pluck('meter_id');
            $readings = Reading::Where('created_at','like', '%'.date('Y-m').'%')->whereIn('meter_id',$industrialNoti)->get();


            $residentConsumers = Consumer::where('consumer_type','01')->pluck('id');
            $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');
            $residentNoti = Notification::whereIn('meter_id',$residentMeters)->pluck('meter_id');
            $readingResidents = Reading::Where('created_at','like', '%'.date('Y-m').'%')->Where('created_at','like', '%'.date('Y-m').'%')->whereIn('meter_id',$residentNoti)->get();

        }
        elseif (auth()->user()->branch_id == 3 || auth()->user()->branch_id == 6) {
            $industrialConsumers = Consumer::where('consumer_type','11')->pluck('id');
            $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');
            $industrialNoti = Notification::where('branch_id',auth()->user()->branch_id)->whereIn('meter_id',$industrialMeters)->pluck('meter_id');
            $readings = Reading::Where('created_at','like', '%'.date('Y-m').'%')->whereIn('meter_id',$industrialNoti)->get();


            $residentConsumers = Consumer::where('consumer_type','01')->pluck('id');
            $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');
            $residentNoti = Notification::where('branch_id',auth()->user()->branch_id)->whereIn('meter_id',$residentMeters)->pluck('meter_id');
            $readingResidents = Reading::Where('created_at','like', '%'.date('Y-m').'%')->whereIn('meter_id',$residentNoti)->get();
        }

        $branches = Branch::all();

        for ($i=0; $i < count($readingResidents); $i++) {

            $meter = Meter::Where('id',$readingResidents[$i]->meter_id)->first();
            $readingResidents[$i]->meter = $meter;

            $meterLocation = MeterLocation::Where('id',$meter->meter_location_id)->first();
            $readingResidents[$i]->meter_location = $meterLocation;


            $consumer = Consumer::Where('id',$meter->consumer_id)->first();
            $readingResidents[$i]->consumer = $consumer;


            $route = Route::Where('id', $consumer->route_id )->first();
            $readingResidents[$i]->route = $route;

            $branch = Branch::where('code', $route->branch_code)->first();
            $readingResidents[$i]->branch = $branch;

            $issue_code = IssueCode::where('id', $readingResidents[$i]->issue_code_id)->first();
            $readingResidents[$i]->issue_code_id = $issue_code;

            $obstacle_code = ObstacleCode::where('code_number', $readingResidents[$i]->obstacle_code)->first();
            $readingResidents[$i]->obstacle_code = $obstacle_code;

        }


        for ($i=0; $i < count($readings); $i++) {

            $meter = Meter::Where('id',$readings[$i]->meter_id)->first();
            $readings[$i]->meter = $meter;

            $meterLocation = MeterLocation::Where('id',$meter->meter_location_id)->first();
            $readings[$i]->meter_location = $meterLocation;


            $consumer = Consumer::Where('id',$meter->consumer_id)->first();
            $readings[$i]->consumer = $consumer;


            $route = Route::Where('id', $consumer->route_id )->first();
            $readings[$i]->route = $route;

            $branch = Branch::where('code', $route->branch_code)->first();
            $readings[$i]->branch = $branch;

            $issue_code = IssueCode::where('id', $readings[$i]->issue_code_id)->first();
            $readings[$i]->issue_code_id = $issue_code;

            $obstacle_code = ObstacleCode::where('code_number', $readings[$i]->obstacle_code)->first();
            $readings[$i]->obstacle_code = $obstacle_code;

            $actualcf = $meter->cf_factor / $meter->z_factor;
            $readings[$i]->actual_cf = $actualcf;

            $one = Reading::where('meter_id',$meter->meter_id)->Where('created_at','like', '%'.date('Y-m',strtotime("-1 month")).'%')->first();
            $two = Reading::where('meter_id',$meter->meter_id)->Where('created_at','like', '%'.date('Y-m',strtotime("-1 month")).'%')->first();
            $three = Reading::where('meter_id',$meter->meter_id)->Where('created_at','like', '%'.date('Y-m',strtotime("-1 month")).'%')->first();

            if ($one != null || $two != null || $three != null || $one != '' || $two != '' || $three != '') {
                $actualAverage = ($one->reading + $two->reading + $three->reading) / 3;
                $readings[$i]->actual_average = $actualAverage;
                $actualConsVar = ($readings[$i]->reading - $actualAverage) / $actualAverage * 100;
                $readings[$i]->actualConsVar = $actualConsVar;
            }
            else
            {
                $readings[$i]->actual_average = 0;
                $readings[$i]->actualConsVar = 0;
            }




                                      // <th>Actual CF</th>
                                      // <th>Actual Consumption Variant</th>
                                      // <th>Average 3 Month Consumption</th>



        }

        $totalMeter = Meter::all();

        $month = date('M Y');

        $unread = count($totalMeter) - count($readings);

        $faulty = Reading::Where('created_at','like', '%'.date('Y-m').'%')->where('issue_code_id',2)->get();

        $noti = Notification::all();

        $reject_code = RejectCode::All();

        $arr = array();
        $arr2 = array();
        $name = array();
        for ($i=0; $i < count($reject_code); $i++) {
            array_push($name, $reject_code[$i]->id);
            array_push($arr2, $reject_code[$i]->description);
        }

        $arr = array_combine($name, $arr2);

        $items = array(
            'readings' => $readings,
            'branches' => $branches,
            'readingResidents' => $readingResidents,
            'noti' => $noti,
            'reject_code' => $arr,
        );
        return view('alert')->with($items);
    }

    public function updateApproval(Request $request)
    {
        $isSuperUser = Auth::user()->type;
        $values = $request->input('reading');
        if($values) {
            if ($isSuperUser==3) {
                foreach ($values as $value) {
                    Reading::where('id', $value)->update([
                        'accepted1' => 2,
                    ]);
                }
            } elseif ($isSuperUser==2) {
                foreach ($values as $value) {
                    Reading::where('id', $value)->update([
                        'accepted2' => 2,
                    ]);
                }
            }
        }

        return redirect()->route('home')
            ->with('success', 'Reading successfully approved!');
    }
}
