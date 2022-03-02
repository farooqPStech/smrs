<?php

namespace App\Http\Livewire;

use Livewire\Component;

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

use Illuminate\Support\Facades\Redirect;


class Home extends Component
{
    public function render()
    {
        $date_check = date('Y-m');
        if(date('d') < 25) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        if (auth()->user()->type == 3 || auth()->user()->type == 6) {

            $individualBranch = Branch::where('id',auth()->user()->branch_id)->first();

            $indidualRoute = Route::where('branch_code',$individualBranch->code)->pluck('id');


            $industrialConsumers = Consumer::with(['meters', 'meters.readings' => function ($query) use ($date_check) {
                    return $query->where('created_at', 'like', '%' . $date_check . '%');
                }])
                ->whereIn('route_id', $indidualRoute)
                ->where('consumer_type', '11')
                ->get();

            // dd($industrialConsumers);

            $residentConsumers = Consumer::with(['meters', 'meters.readings' => function ($query) use ($date_check) {
                    return $query->where('created_at', 'like', '%' . $date_check . '%');
                }])
                ->whereIn('route_id', $indidualRoute)
                ->where('consumer_type', '01')
                ->pluck('id');


            $industrialConsumers = Consumer::whereIn('route_id',$indidualRoute)->where('consumer_type','11')->pluck('id');
            $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');
            $readings = Reading::select('id','reading','meter_id','issue_code_id','obstacle_code_id','mmbtu','image','current_consumption')
            ->where('created_at','like','%'.$date_check.'%')
            ->whereIn('meter_id',$industrialMeters)
            ->get();

            $residentConsumers = Consumer::whereIn('route_id',$indidualRoute)->where('consumer_type','01')->pluck('id');
            $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');
            $readingResidents = Reading::select('id','reading','meter_id','issue_code_id','image','current_consumption')->where('created_at','like','%'.$date_check.'%')->whereIn('meter_id',$residentMeters)->get();
        }
        else
        {

            $industrialConsumers = Consumer::where('consumer_type','11')->pluck('id');
            $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');

            $readings = Reading::with(['meter','meter.meterLocation','obstacleCode','issueCode','meter.consumer'])
            ->where('readings.created_at','like','%'.$date_check.'%')
                // ->where('consumer_type', '11')
            ->join('meters', 'readings.meter_id', 'meters.id')
            ->join('consumers', 'consumers.id','meters.consumer_id')
            ->where('consumers.consumer_type', '11')
                // ->take(10)
                ->get();
            // dd(count($readings));

            $readingResidents = Reading::with(['meter','meter.meterLocation','obstacleCode','issueCode','meter.consumer'])
            ->where('readings.created_at','like','%'.$date_check.'%')
                // ->where('consumer_type', '11')
            ->join('meters', 'readings.meter_id', 'meters.id')
            ->join('consumers', 'consumers.id','meters.consumer_id')
            ->where('consumers.consumer_type', '01')
                // ->take(10)
                ->get();



                // dd($readings[0]);



            // $readings = Reading::select('id','reading','meter_id','issue_code','obstacle_code','mmbtu','image','current_consumption')->where('created_at','like','%'.$date_check.'%')->whereIn('meter_id',$industrialMeters)->get();

            // $residentConsumers = Consumer::where('consumer_type','01')->pluck('id');
            // $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');
            // $readingResidents = Reading::select('id','reading','meter_id','issue_code','image','current_consumption')->where('created_at','like','%'.$date_check.'%')->whereIn('meter_id',$residentMeters)->get();
        }

        for ($i=0; $i < count($readingResidents); $i++) {

             $meter = Meter::select('id','meter_type','meter_number','meter_location_id','consumer_id','z_factor','cf_factor')->Where('id',$readingResidents[$i]->meters[0]->id)->first();
            // $readingResidents[$i]->meter = $meter;

            // $meterLocation = MeterLocation::Where('id',$meter->meter_location_id)->first();
            // $readingResidents[$i]->meter_location = $meterLocation;

             $consumer = Consumer::Where('id',$meter->consumer_id)->first();
            // $readingResidents[$i]->consumer = $consumer;

            $route = Route::Where('id', $consumer->route_id )->first();
            $readingResidents[$i]->route = $route;

            $branch = Branch::where('code', $route->branch_code)->first();
            $readingResidents[$i]->branch = $branch;

            // $issue_code = IssueCode::where('code_number', $readingResidents[$i]->issue_code)->first();
            // if ($issue_code) {
            //     $readingResidents[$i]->issue_code = $issue_code->description;
            // }

            // $obstacle_code = ObstacleCode::select('description')->where('code_number', $readingResidents[$i]->obstacle_code)->first();
            // if ($obstacle_code) {
            //     $readings[$i]->obstacle_code = $obstacle_code;
            // }

        }
        for ($i=0; $i < count($readings); $i++) {

            // dd($readings[0]->meters[0]->readings[0]->obstacleCode->description);
            // $readings[$i]->meter = $readings[$i]->meters;

            // $readings[$i]->meter_location = $meterLocation;

            // $consumer = Consumer::select('consumer_type','consumer_number','consumer_name','area_code','route_id')->Where('id',$meter->consumer_id)->first();
            // $readings[$i]->consumer = $consumer;
            $route = Route::select('branch_code')->Where('id', $readings[$i]->route_id )->first();
            if($route) {
                $branch = Branch::select('id','name')->where('code', $route->branch_code)->first();
                $readings[$i]->branch = $branch;
            }
            // dd($readings[$i]);

            // $issue_code = IssueCode::select('description')->where('code_number', $readings[$i]->issue_code)->first();
            // $readings[$i]->issue_code = $issue_code;

            // $obstacle_code = ObstacleCode::select('description')->where('code_number', $readings[$i]->obstacle_code)->first();
            // if ($obstacle_code) {
            //     $readings[$i]->obstacle_code = $obstacle_code->description;
            // }


            if ($readings[$i]->meter->z_factor) {
                $actualcf = $readings[$i]->meter->cf_factor / $readings[$i]->meter->z_factor;
                $readings[$i]->actual_cf = $actualcf;
            }

            if ($readings[$i]->meter->meter_type == 0 || $readings[$i]->meter->meter_type == '0' || $readings[$i]->meter->meter_type == 2 || $readings[$i]->meter->meter_type == '2') {
                $one = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-1 month")).'%')->first();
                $two = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-2 month")).'%')->first();
                $three = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-3 month")).'%')->first();
                $four = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-4 month")).'%')->first();
                $five = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-5 month")).'%')->first();
                $six = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-6 month")).'%')->first();

                $a  = 0;
                $b  = 0;
                $c  = 0;

                if ($one == null) {
                    $a = 0;
                }
                else
                {
                    $a = $one->current_consumption;
                }
                if ($two == null) {
                    $b = 0;
                }
                else
                {
                    $b = $two->current_consumption;
                }
                if ($three == null) {
                    $c = 0;
                }
                else
                {
                    $c = $three->current_consumption;
                }

                if ($a == 0 && $b == 0 && $c == 0) {
                    $actualAverage = 0;
                    $ConCF = 0;
                }
                else
                {
                    $actualAverage = ($a + $b + $c) / 3;
                    $ConCF = ($readings[$i]->current_consumption - $actualAverage) / $actualAverage * 100;
                }
                if ($ConCF != 0) {
                    $readings[$i]->cfVariant = ($actualcf - $ConCF)/$ConCF;
                }
                else
                {
                    $readings[$i]->cfVariant = 0;
                }
                $readings[$i]->actual_average = $actualAverage;
                $readings[$i]->ConCF = $ConCF;
            }
            else
            {
                $one = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-1 month")).'%')->first();
                $two = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-2 month")).'%')->first();
                $three = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-3 month")).'%')->first();
                $four = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-4 month")).'%')->first();
                $five = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-5 month")).'%')->first();
                $six = Reading::select('current_consumption')->where('meter_id',$readings[$i]->meter->id)->Where('created_at','like', '%'.date('Y-m',strtotime("-6 month")).'%')->first();

                $a  = 0;
                $b  = 0;
                $c  = 0;

                if ($one == null) {
                    $a = 0;
                }
                else
                {
                    $a = $one->current_consumption;
                }
                if ($two == null) {
                    $b = 0;
                }
                else
                {
                    $b = $two->current_consumption;
                }
                if ($three == null) {
                    $c = 0;
                }
                else
                {
                    $c = $three->current_consumption;
                }

                if ($a == 0 && $b == 0 && $c == 0) {
                    $actualAverage = 0;
                    $ConCF = 0;
                }
                else
                {
                    $actualAverage = ($a + $b + $c) / 3;
                    $ConCF = ($readings[$i]->current_consumption - $actualAverage) / $actualAverage * 100;
                }
                $readings[$i]->ConCF = $ConCF;
                if ($ConCF != 0) {
                    $readings[$i]->cfVariant = ($readings[$i]->actual_cf - $ConCF)/$ConCF;
                }
                else
                {
                    $readings[$i]->cfVariant = 0;
                }

                $readings[$i]->actual_average = 'Meter Type 0 Only';
                $readings[$i]->ConCF = 0;
            }
        }

        $totalMeter = Meter::select('id')->get();

        $month = date('M',strtotime("-1 month"));

        $unread = count($totalMeter) - count($readings);

        $faulty = Reading::select('id')->where('created_at','like','%'.$date_check.'%')->where('issue_code_id',2)->get();

        $noti = Notification::select('title','content')->where('created_at','like','%'.$date_check.'%')->get();

        $reject_code = RejectCode::select('id','description')->get();

        $branches = Branch::select('id','name')->get();

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
            'totalMeter' => count($totalMeter),
            'totalReading' => count($readings),
            'currentMonth' => $month,
            'unread' => $unread,
            'faulty' => count($faulty),
            'noti' => $noti,
            'reject_code' => $arr,
        );


        return view('livewire.home', [
            'readings' => $readings,
            'branches' => $branches,
            'readingResidents' => $readingResidents,
            'totalMeter' => count($totalMeter),
            'totalReading' => count($readings),
            'currentMonth' => $month,
            'unread' => $unread,
            'faulty' => count($faulty),
            'noti' => $noti,
            'reject_code' => $arr,
        ]);
    }
}
