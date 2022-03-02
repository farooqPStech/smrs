<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reading;
use App\Models\Branch;
use App\Models\Meter;
use App\Models\MeterLocation;
use App\Models\Consumer;
use App\Models\Route;
use App\Models\IssueCode;
use App\Models\ObstacleCode;


class MeterController extends Controller
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
    public function list()
    {

        if (auth()->user()->type == 3 || auth()->user()->type == 6) {

                $individualBranch = Branch::where('id',auth()->user()->branch_id)->first();
                $indidualRoute = Route::where('branch_code',$individualBranch->code)->pluck('id');

                $industrialConsumers = Consumer::whereIn('route_id',$indidualRoute)->where('consumer_type','11')->pluck('id');
                $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');
                $readings = Reading::whereIn('meter_id',$industrialMeters)->get();

                $residentConsumers = Consumer::whereIn('route_id',$indidualRoute)->where('consumer_type','01')->pluck('id');
                $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');                
                $readingResidents = Reading::whereIn('meter_id',$residentMeters)->get();

         }
        else
        {

                $industrialConsumers = Consumer::where('consumer_type','11')->pluck('id');
                $industrialMeters = Meter::whereIn('consumer_id',$industrialConsumers)->pluck('id');
                $readings = Reading::whereIn('meter_id',$industrialMeters)->get();


                $residentConsumers = Consumer::where('consumer_type','01')->pluck('id');
                $residentMeters = Meter::whereIn('consumer_id',$residentConsumers)->pluck('id');                
                $readingResidents = Reading::whereIn('meter_id',$residentMeters)->get();
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

            $issue_code = IssueCode::find($readingResidents[$i]->issue_code_id);
            $readingResidents[$i]->issue_code = $issue_code;

            $obstacle_code = ObstacleCode::find($readingResidents[$i]->obstacle_code_id);
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

            $issue_code = IssueCode::find($readings[$i]->issue_code_id);
            $readings[$i]->issue_code = $issue_code;

            $obstacle_code = ObstacleCode::find($readings[$i]->obstacle_code_id);
            $readings[$i]->obstacle_code = $obstacle_code;

        }

         $items = array(
            'readings' => $readings,
            'branches' => $branches,
            'readingResidents' => $readingResidents,

        );
        return view('meter.list')->with($items);
    }
     public function viewTrend($meterId)
    {

        $readings = Reading::where('meter_id',$meterId)->get();

        $meter1 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 1)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter2 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 2)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter3 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 3)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter4 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 4)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter5 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 5)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter6 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 6)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter7 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 7)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter8 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 8)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter9 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 9)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter10 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 10)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter11 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 11)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
                        ->first();
        $meter12 = Reading::where('meter_id',$meterId)
                        ->whereMonth('created_at', '=', 12)
                        ->whereYear('created_at', '=', date('Y'))
                        ->pluck('reading')
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

        return view('meter.viewTrend')->with($items);
    }
}
