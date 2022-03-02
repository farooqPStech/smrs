<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Broadcast;
use App\Models\Handheld;
use App\Models\Reading;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Log;
use App\Models\Bill;
use Illuminate\Support\Facades\Redirect;



class ConsumerController extends Controller
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

        return view('consumer.list');
    }

    public function industrialList()
    {

        if(request()->ajax()) {
            $query = Consumer::select('*')->where('consumer_type','11');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            // ->addColumn('status', function ($query) {
            //     return $query->status_str();
            //   })
            ->addColumn('action', function ($query) {
                return '
                <a href="/consumer/view/'.$query->id.'" class="btn btn-smrs btn-primary"><i class="fa fas fa-edit"></i> View</a>';
            })
            ->editColumn('created_at', function ($request) {
            return $request->created_at->format('d-M-Y'); // human readable format
            })
            ->rawColumns(['action'])
            ->toJson();
        }
    }

    public function residentList()
    {

        if(request()->ajax()) {
            $query = Consumer::select('*')->where('consumer_type','01');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            // ->addColumn('status', function ($query) {
            //     return $query->status_str();
            //   })
            ->addColumn('action', function ($query) {
                return '
                <a href="/consumer/view/'.$query->id.'" class="btn btn-smrs btn-primary"><i class="fa fas fa-edit"></i> View</a>';
            })
            ->editColumn('created_at', function ($request) {
            return $request->created_at->format('d-M-Y'); // human readable format
            })
            ->rawColumns(['action'])
            ->toJson();
        }
    }

    public function view($id)
    {
        $consumer = Consumer::find($id);

        $allMeter = Meter::where('consumer_id',$id)->get();
        $allBill = Bill::where('consumer_id',$id)->get();

        // $meterId = 1;

        for ($i=0; $i < count($allMeter); $i++) { 
            // code...

            $meter1[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 1)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            
                // $meter1[$i] = $meter1[$i]->current_consumption;

            $meter2[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 2)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter3[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 3)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter4[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 4)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter5[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 5)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter6[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 6)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter7[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 7)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter8[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 8)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter9[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 9)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter10[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 10)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter11[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 11)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();
            $meter12[$i] = Reading::where('meter_id',$allMeter[$i]->id)
                            ->whereMonth('created_at', '=', 12)
                            ->whereYear('created_at', '=', date('Y'))
                            ->pluck('current_consumption')
                            ->first();

            $meterName[$i] = $allMeter[$i]->meter_number;
            $color[$i] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }

        $arr = array();

        for ($i=0; $i < count($allMeter); $i++) { 
        
            if ($meter1[$i] == null) {
                $meter1[$i] = 0;
            }
            if ($meter2[$i] == null) {
                $meter2[$i] = 0;
            }
            if ($meter3[$i] == null) {
                $meter3[$i] = 0;
            }
            if ($meter4[$i] == null) {
                $meter4[$i] = 0;
            }
            if ($meter5[$i] == null) {
                $meter5[$i] = 0;
            }
            if ($meter6[$i] == null) {
                $meter6[$i] = 0;
            }
            if ($meter7[$i] == null) {
                $meter7[$i] = 0;
            }
            if ($meter8[$i] == null) {
                $meter8[$i] = 0;
            }
            if ($meter9[$i] == null) {
                $meter9[$i] = 0;
            }
            if ($meter10[$i] == null) {
                $meter10[$i] = 0;
            }
            if ($meter11[$i] == null) {
                $meter11[$i] = 0;
            }
            if ($meter12[$i] == null) {
                $meter12[$i] = 0;
            }

            $arr2 = array();
            $arr3 = array();

            $name = array('name', 'data', 'color');

            array_push($arr3, $meter1[$i]);
            array_push($arr3, $meter2[$i]);
            array_push($arr3, $meter3[$i]);
            array_push($arr3, $meter4[$i]);
            array_push($arr3, $meter5[$i]);
            array_push($arr3, $meter6[$i]);
            array_push($arr3, $meter7[$i]);
            array_push($arr3, $meter8[$i]);
            array_push($arr3, $meter9[$i]);
            array_push($arr3, $meter10[$i]);
            array_push($arr3, $meter11[$i]);
            array_push($arr3, $meter12[$i]);

            array_push($arr2, $meterName[$i]);
            array_push($arr2, $arr3);
            array_push($arr2, $color[$i]);

            // array_push($arr, array_combine($name, $arr2));

            array_push($arr, array_combine($name, $arr2));
           


            // dd($arr);
        }

            $arr4 = array();
            $arr5 = array();

            $allConsumerMeter = Meter::select('id')->where('consumer_id',$id)->get()->toArray();

            // dd($allConsumerMeter);
            $sum1 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 1)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum2 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 2)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum3 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 3)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum4 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 4)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum5 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 5)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum6 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 6)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum7 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 7)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum8 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 8)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum9 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 9)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum10 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 10)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum11 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 11)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');

            $sum12 = Reading::whereIn('meter_id',$allConsumerMeter)
                            ->whereMonth('created_at', '=', 12)
                            ->whereYear('created_at', '=', date('Y'))
                            ->sum('mmbtu');                            

            array_push($arr5, intval($sum1));
            array_push($arr5, intval($sum2));
            array_push($arr5, intval($sum3));
            array_push($arr5, intval($sum4));
            array_push($arr5, intval($sum5));
            array_push($arr5, intval($sum6));
            array_push($arr5, intval($sum7));
            array_push($arr5, intval($sum8));
            array_push($arr5, intval($sum9));
            array_push($arr5, intval($sum10));
            array_push($arr5, intval($sum11));
            array_push($arr5, intval($sum12));

            array_push($arr4, 'SUM');
            array_push($arr4, $arr5);
            array_push($arr4, '$000000');

            array_push($arr, array_combine($name, $arr4));

          // dd($arr);

        // $consumer = Consumer::Where('id',$meterDetail->consumer_id)->first();


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
            'meterName' => $meterName,
            'consumer' => $consumer,
            'allMeter' => $allMeter,
            'allBill' => $allBill,
            'color' => $color,
            'arr' => $arr,
        );



        return view('consumer.view')->with($items);   
    }
}
