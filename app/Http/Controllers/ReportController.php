<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Route;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\GCPT;
use App\Models\Bill;
use App\Models\Log;
use App\User;
use App\Models\Reading;
use App\Models\Highlow;
use App\Models\Price;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
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
    public function billingDetailsByRoute()
    {
        return view('report.billingDetailsByRoute');
    }

    public function billingDetailsSummaryByRoute()
    {
        return view('report.billingDetailsSummaryByRoute');
    }

    public function cancelBillListing()
    {
        return view('report.cancelBillListing');
    }

    public function billingDetailsByRouteGMSB()
    {
        return view('report.billingDetailsByRouteGMSB');
    }

    public function KIVBillListing()
    {
        return view('report.KIVBillListing');
    }

    public function loginLogout()
    {
        return view('report.loginLogout');
    }

    public function meterRangeCheck()
    {
        return view('report.meterRangeCheck');
    }

    public function meterRangeCheckSummary()
    {
        return view('report.meterRangeCheckSummary');
    }

    public function dailySalesPerformanceByRoute()
    {
        return view('report.dailySalesPerformanceByRoute');
    }

    public function dailySalesPerformanceByTariff()
    {
        return view('report.dailySalesPerformanceByTariff');
    }

    public function readingInterval()
    {
        return view('report.readingInterval');
    }

    public function readingIntervalSummary()
    {
        return view('report.readingIntervalSummary');
    }

    //Table section

    public function billingDetailsByRouteTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {

            $data = Consumer::select('*')->where('consumer_type','11')->pluck('id');

            $query = Meter::Wherein('consumer_id',$data);
             // $query = Meter::leftjoin('consumers','consumers.id', '=', 'meters.consumer_id')->leftjoin('readings','readings.meter_id', '=', 'meters.id')->leftjoin('routes','routes.id', '=', 'consumers.route_id')->select('meters.*','consumers.consumer_address_1','routes.route','readings.reading', 'readings.created_at','consumers.tariff_code','readings.meter_reader_id');


            return datatables()->eloquent($query)
            // ->addIndexColumn()
            // ->addColumn('handheld', function ($query) {
                // return $query->uuid;
              // })

            ->editColumn('route', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->pluck('route_id');
                    $route = Route::where('id',$consumer)->pluck('route');
                    return $route[0];
                })


              ->editColumn('consumer_address_1', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->consumer_address_1;
                })

              ->editColumn('last_reading_date', function($query) {
                    if($query->last_reading_date != null)
                    {
                        return date('Y-m-d',strtotime($query->last_reading_date));
                    }
                })

              ->editColumn('reading', function($query) {

                    $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }
                    $reading = Reading::where('meter_id',$query->id)->Where('created_at','like', '%'.$date_check.'%')->first();
                    if ($reading) {
                        // code...
                    return $reading->reading;
                    }
                })

              ->editColumn('current', function($query) {
                $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                 $reading = Reading::where('meter_id',$query->id)->Where('created_at','like', '%'.$date_check.'%')->first();
                    if ($reading) {
                    return $reading->reading - $query->last_reading;
                    }
                    else
                    {
                        return null;
                    }
                })

              ->editColumn('created_at', function($query) {
                    if($query->created_at != null)
                    {
                        return date('Y-m-d',strtotime($query->created_at));
                    }
                })

                ->editColumn('mmbtu', function($query) {

                    $totalMeter = Meter::Where('consumer_id',$query->consumer_id)->get();
                    // dd($totalMeter);

                    $total = count($totalMeter) / 2;
                        for ($i=0; $i < $total; $i++) {

                            for ($j=0; $j < $total; $j++) {

                                if ($totalMeter[$i]->meter_location_id == $totalMeter[$j]->meter_location_id) {

                                    if ($totalMeter[$i]->meter_type == 1) {

                                        $reading1 = Reading::where('meter_id', $totalMeter[$i]->id)->first();
                                        $reading2 = Reading::where('meter_id', $totalMeter[$j]->id)->first();

                                        if ($reading1 == null) {
                                            if ($reading2 == null) {
                                            return null;
                                            }
                                        }

                                        $val1 = intval($reading1->reading) - intval($query->last_reading);
                                        $val2 = intval($reading2->reading) - intval($query->last_reading);

                                        if ($val1 > $val2) {
                                            return $val1;
                                        }
                                        else
                                        {
                                            return $val2;
                                        }
                                    }
                                    elseif ($totalMeter[$i]->meter_type == null) {
                                        return null;
                                    }
                                }
                            }
                        }
                })

                ->editColumn('tariff_code', function($query) {
                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->tariff_code;
                })
                ->editColumn('current_bill_amount', function($query) {
                    $bill = Bill::where('consumer_id',$query->consumer_id)->first();

                    if ($bill) {
                        return $bill->bill_amount;
                    }
                })
                ->editColumn('gcpt', function($query) {
                    $gcpt = GCPT::select('*')->first();

                    if ($gcpt) {
                        return $gcpt->rate;
                    }
                })
                ->editColumn('time', function($query) {
                   return date('h:m',strtotime($query->created_at));
                })
                ->editColumn('mrid', function($query) {
                    $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_id',$query->id)->Where('created_at','like', '%'.$date_check.'%')->first();
                    if ($reading) {
                        return $reading->meter_reader_id;
                    }
                })
                ->editColumn('remark', function($query) {
                   return null;
                })


            ->toJson();
        }
    }

     public function billingDetailsSummaryByRouteTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
            $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function cancelBillListingTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function billingDetailsByRouteGMSBTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function KIVBillListingTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function loginLogoutTable()
    {
        $login = 'login';
        if(request()->ajax()) {
        $user = User::where('type','=',4)->pluck('id');
            // $query = Route::select('*');
        $query = Log::Where('activity','like', '%'.$login.'%')->whereIn('user',$user);
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->editColumn('name', function($query) {
                  $user = User::find($query->user);
                  if ($user) {
                    return $user->full_name;
                  }

            })
            ->editColumn('read', function($query) {
                $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();
                // $reading = Reading::where('meter_reader_id',$query->user)->get();

                // $reading = Reading::get();

                if ($reading) {
                    return $reading->count();
                }
                else
                {
                    return 0;
                }


            })


            ->editColumn('noaccess', function($query) {


                $noaccess = Reading::where('meter_reader_id',$query->user)->where('id',$query->id)->where('issue_code_id',3)->get();

                return count($noaccess);

            })

            ->editColumn('unread', function($query) {

            $readings = Reading::select('*')->leftjoin('meters' , 'readings.meter_id', '=', 'meters.id')->leftjoin('consumers', 'consumers.id', '=', 'meters.consumer_id')->leftjoin('handheld_routes','handheld_routes.route_id', '=', 'consumers.route_id')->get();

            $reading = Reading::get();

                return count($readings) - count($reading);

            })

            ->editColumn('time', function($query) {

                $logout = 'logout';

                $log = Log::Where('activity','like', '%'.$logout.'%')->where('user',$query->user)->first();

                if ($log) {

                    $startTime = new DateTime($query->created_at);
                    $endTime = new DateTime('2021-04-01 01:30');

                    $interval = date_diff($startTime, $endTime);

                    // dd($interval);

                    return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';

                }
                else
                {
                    return 'User Not Logout';
                }
            })

             ->editColumn('logout', function($query) {

                $logout = 'logout';

                $log = Log::Where('activity','like', '%'.$logout.'%')->where('user',$query->user)->first();

                if ($log) {

                    return $log->created_at;
                }
                else
                {
                    return 'User Not Logout';
                }

            })

            ->toJson();
        }
    }

    public function meterRangeCheckTable()
    {
        if(request()->ajax()) {

            $data = Consumer::select('*')->where('consumer_type','11')->pluck('id');

            $query = Meter::Wherein('consumer_id',$data);
             // $query = Meter::leftjoin('consumers','consumers.id', '=', 'meters.consumer_id')->leftjoin('readings','readings.meter_id', '=', 'meters.id')->leftjoin('routes','routes.id', '=', 'consumers.route_id')->select('meters.*','consumers.consumer_address_1','routes.route','readings.reading', 'readings.created_at','consumers.tariff_code','readings.meter_reader_id');


            return datatables()->eloquent($query)
            ->addIndexColumn()
            // ->addColumn('handheld', function ($query) {
                // return $query->uuid;
              // })

            ->editColumn('route', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->pluck('route_id');
                    $route = Route::where('id',$consumer)->pluck('route');
                    return $route[0];
                })


                ->editColumn('consumer_number', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->consumer_number;
                })
              ->editColumn('consumer_address_1', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->consumer_address_1;
                })


              // ->editColumn('meter', function($query) {
              //       if($query->last_reading_date != null)
              //       {
              //           return date('Y-m-d',strtotime($query->last_reading_date));
              //       }
              //   })


               ->editColumn('low', function($query) {

                $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

                $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

                if ($highlow) {
                    // code...
                     $date_check = date('Y-m');
                        if(date('d') < 22) {
                            $date_check = date('Y-m', strtotime('-28 days'));
                        } else {
                            $date_check = date('Y-m');
                        }

                    $readings = Reading::where('meter_id',$query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($readings) {
                        $val = intval($readings->reading) - ($readings->reading * ($highlow->low_consumption / 100));

                       if ($val) {
                           return $val;
                       }
                       else
                       {
                            return 'Calculation Error';
                        }

                    }
                    else
                    {
                        return 'No Reading Yet';
                    }
                }
                else
                {
                    return 'Low Control Value Not Found';
                }
            })

            ->editColumn('high', function($query) {

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

                $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

                if ($highlow) {
                    // code...
                     $date_check = date('Y-m');
                        if(date('d') < 22) {
                            $date_check = date('Y-m', strtotime('-28 days'));
                        } else {
                            $date_check = date('Y-m');
                        }

                    $readings = Reading::where('meter_id',$query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($readings) {
                        $val = intval($readings->reading) - ($readings->reading * ($highlow->high_consumption / 100));

                       if ($val) {
                           return $val;
                       }
                       else
                       {
                            return 'Calculation Error';
                        }

                    }
                    else
                    {
                        return 'No Reading Yet';
                    }
                }
                else
                {
                    return 'High Control value Not Found';
                }

            })


                ->editColumn('reading', function($query) {

                    $totalMeter = Meter::Where('consumer_id',$query->consumer_id)->get();
                    // dd($totalMeter);

                    $total = count($totalMeter) / 2;
                        for ($i=0; $i < $total; $i++) {

                            for ($j=0; $j < $total; $j++) {

                                if ($totalMeter[$i]->meter_location_id == $totalMeter[$j]->meter_location_id) {

                                    if ($totalMeter[$i]->meter_type == 1) {

                                        $reading1 = Reading::where('meter_id', $totalMeter[$i]->id)->first();
                                        // $reading2 = Reading::where('meter_id', $totalMeter[$j]->id)->first();

                                        if ($reading1 == null) {
                                            // if ($reading2 == null) {
                                            return 0;
                                            // }
                                        }

                                        $val1 = intval($reading1->reading);
                                        // $val2 = intval($reading2->reading) - intval($query->last_reading);

                                        // if ($val1 > $val2) {
                                            return $val1;
                                        // }
                                        // else
                                        // {
                                            // return $val2;
                                        // }
                                    }
                                    elseif ($totalMeter[$i]->meter_type == null) {
                                        return 0;
                                    }
                                }
                            }
                        }
                })

                ->editColumn('consumption', function($query) {

                        $totalMeter = Meter::Where('consumer_id',$query->consumer_id)->get();
                        // dd($totalMeter);

                        $total = count($totalMeter) / 2;
                            for ($i=0; $i < $total; $i++) {

                                for ($j=0; $j < $total; $j++) {

                                    if ($totalMeter[$i]->meter_location_id == $totalMeter[$j]->meter_location_id) {

                                        if ($totalMeter[$i]->meter_type == 1) {

                                            $reading1 = Reading::where('meter_id', $totalMeter[$i]->id)->first();
                                            $reading2 = Reading::where('meter_id', $totalMeter[$j]->id)->first();

                                            if ($reading1 == null) {
                                                if ($reading2 == null) {
                                                return 0;
                                                }
                                            }

                                            $val1 = intval($reading1->reading) - intval($query->last_reading);
                                            $val2 = intval($reading2->reading) - intval($query->last_reading);

                                            if ($val1 > $val2) {
                                                return $val1;
                                            }
                                            else
                                            {
                                                return $val2;
                                            }
                                        }
                                        elseif ($totalMeter[$i]->meter_type == null) {
                                            return 0;
                                        }
                                    }
                                }
                            }
                    })
                ->editColumn('code', function($query) {
                    if ($query->last_reading_status == '00') {
                        return 'N';
                    }
                    else
                    {
                        return 'E';
                    }

                })
                ->editColumn('mrid', function($query) {
                    $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_id',$query->id)->first();
                    if ($reading) {
                        $user = User::find($reading->meter_reader_id);
                        return $user->name.' ('.$reading->meter_reader_id.')';
                    }
                    else
                    {
                        return 0;
                    }
                })
                ->editColumn('remark', function($query) {
                   return null;
                })


            ->toJson();
        }
    }

    public function meterRangeCheckSummaryTable()
    {
        $route = Route::all();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $consumer = Consumer::select('*')->where('consumer_type','11')->pluck('route_id');
        $query = Route::select('*')->where('sub',null)->where('combine',null)->whereIn('id',$consumer);

            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->editColumn('read_date', function($query) {

               $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($reading) {

                    return date('Y-m-d', strtotime( $log->created_at));
                }
                else
                {
                    return 'Not Read Yet';
                }

            })
            ->editColumn('total_meter', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               return count($meters);

            })

            ->editColumn('zero', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $readings = Reading::whereIn('meter_id',$meters)->where('reading', 0)->get();

               return count($readings);

            })

            ->editColumn('low', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

               $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

                if ($highlow) {
                    // code...
                     $date_check = date('Y-m');
                        if(date('d') < 22) {
                            $date_check = date('Y-m', strtotime('-28 days'));
                        } else {
                            $date_check = date('Y-m');
                        }

                    $readings = Reading::whereIn('meter_id',$meters)->Where('created_at','like', '%'.$date_check.'%')->get();

                    if ($readings) {

                        $countLow = 0;

                        for ($i=0; $i < count($readings); $i++) {

                            $val = intval($readings[$i]->reading) - ($readings[$i]->reading * ($highlow->low_consumption / 100));

                            if ($val < $readings[$i]->reading) {
                                $countLow++;
                            }
                        }

                        return $countLow;

                    }
                    else
                    {
                        return 'No Reading Yet';
                    }
                }
                else
                {
                    return 'Low Control value Not Found';
                }

            })

            ->editColumn('high', function($query) {

                             $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

               $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

                if ($highlow) {
                    // code...
                     $date_check = date('Y-m');
                        if(date('d') < 22) {
                            $date_check = date('Y-m', strtotime('-28 days'));
                        } else {
                            $date_check = date('Y-m');
                        }

                    $readings = Reading::whereIn('meter_id',$meters)->Where('created_at','like', '%'.$date_check.'%')->get();

                    if ($readings) {

                        $countHigh = 0;

                        for ($i=0; $i < count($readings); $i++) {

                            $val = intval($readings[$i]->reading) - ($readings[$i]->reading * ($highlow->high_consumption / 100));

                            if ($val > $readings[$i]->reading) {
                                $countHigh++;
                            }
                        }

                        return $countHigh;

                    }
                    else
                    {
                        return 'No Reading Yet';
                    }
                }
                else
                {
                    return 'High Control value Not Found';
                }

            })

            ->editColumn('negative', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

               $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

               if ($highlow) {
                   $readings = Reading::whereIn('meter_id',$meters)->where('reading', 0)->get();
                    return count($readings);
               }
               else
               {
                    return 'Value Not Found';
               }


            })

            ->editColumn('length_err', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

               $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

               // if ($highlow) {
                   $readings = Reading::whereIn('meter_id',$meters)->get();
                   $val = 0;

                   for ($i=0; $i < count($readings); $i++) {
                       if ($readings[$i]->reading < 0  || $readings[$i]->reading > 11 ) {
                           $val += 1;
                       }
                   }
                   return $val;
               // }
               // else
               // {
               //      return 'Value Not Found';
               // }
            })


            ->editColumn('too_high', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

               $tariff = Price::where('effective_date',Price::max('effective_date'))->orderBy('effective_date','desc')->first();

               $highlow = Highlow::where('tariff_code',$tariff->tariff_Code)->first();

               if ($highlow) {
                   $readings = Reading::whereIn('meter_id',$meters)->where('reading','>',$highlow->high_consumption*2)->get();
               }
               else
               {
                    return 'Too High Value Not Found';
               }


               return count($readings);
            })

            ->toJson();
        }
    }

    public function dailySalesPerformanceByRouteTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function dailySalesPerformanceByTariffTable()
    {
        $branch = Branch::where('id',auth()->user()->branch_id)->first();

        if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->addColumn('handheld', function ($query) {
                return $query->uuid;
              })

            ->toJson();
        }
    }

    public function readingIntervalTable()
    {

        if(request()->ajax()) {

            $data = Consumer::select('*')->where('consumer_type','11')->pluck('id');

            $query = Meter::Wherein('consumer_id',$data);


            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->editColumn('route', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->pluck('route_id');
                    $route = Route::where('id',$consumer)->pluck('route');
                    return $route[0];
                })

                ->editColumn('consumer_number', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->consumer_number;
                })
              ->editColumn('consumer_address_1', function($query) {

                    $consumer = Consumer::where('id',$query->consumer_id)->first();

                    return $consumer->consumer_address_1;
                })
                ->editColumn('time_1', function($query) {

                    $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                    $reading = Reading::where('meter_id',$query->meter_id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($reading) {
                        return date('h:m', strtotime($reading->created_at));
                    }
                    else{
                        return 'Not Read Yet';
                    }
                })
              ->editColumn('consumer_number_2', function($query) {

                     $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }
                    $reading = Reading::where('meter_id',$query->meter_id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($reading) {

                        $readingNext = Reading::where('meter_reader_id',$reading->meter_reader_id)->orderBy('created_at', 'desc')->pluck('meter_id');

                        if ($readingNext) {

                            $meter = Meter::where('id',$readingNext[1])->first();

                            $consumer = Consumer::where('id',$meter->consumer_id)->first();

                            return  $consumer->consumer_number;
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                    }
                    else
                    {
                        return 'Not Yet Read';
                    }
                })
              ->editColumn('consumer_address_1_2', function($query) {

                    $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }
                    $reading = Reading::where('meter_id',$query->meter_id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($reading) {

                        $readingNext = Reading::where('meter_reader_id',$reading->meter_reader_id)->orderBy('created_at', 'desc')->pluck('meter_id');

                        if ($readingNext) {

                            $meter = Meter::where('id',$readingNext[1])->first();

                            $consumer = Consumer::where('id',$meter->consumer_id)->first();

                            return  $consumer->consumer_address_1;
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                    }
                    else
                    {
                        return 'Not Yet Read';
                    }
                })
              ->editColumn('time_2', function($query) {

                     $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }
                    $reading = Reading::where('meter_id',$query->meter_id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($reading) {

                        $readingNext = Reading::where('meter_reader_id',$reading->meter_reader_id)->orderBy('created_at', 'desc')->pluck('created_at');

                        if ($readingNext) {

                            return  $readingNext[1];
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                    }
                    else
                    {
                        return 'Not Yet Read';
                    }
                })
                ->editColumn('interval', function($query) {


                    $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }
                    $reading = Reading::where('meter_id',$query->meter_id)->Where('created_at','like', '%'.$date_check.'%')->first();

                    if ($reading) {

                        $readingNext = Reading::where('meter_reader_id',$reading->meter_reader_id)->orderBy('created_at', 'desc')->pluck('meter_id');

                        if ($readingNext) {

                                $startTime = new DateTime($readingNext[0]);
                                 $endTime = new DateTime($readingNext[1]);


                                 $interval = date_diff($startTime, $endTime);

                                 return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                    }
                    else
                    {
                        return 'Not Yet Read';
                    }


                    // $consumer1 = Consumer::where('route_id',$query->route_id)->where('id',$query->consumer_id)->first();
                    // $consumer2 = Consumer::where('route_id',$query->route_id)->where('id',$query->consumer_id + 1)->first();

                    // $reading = Reading::where('meter_id',$query->meter_id)->first();
                    // $reading2 = Reading::where('meter_id',$query->meter_id+1)->first();

                    // if ($reading2) {
                    // $startTime = new DateTime($reading->created_at);
                    // $endTime = new DateTime($reading2->created_at);


                    // $interval = date_diff($startTime, $endTime);

                    // return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';
                    // }
                    // else
                    // {
                        return 0;
                    // }


                    // return date('h:m', strtotime($consumer->created_at));
                })

            ->toJson();
        }
    }

    public function readingIntervalSummaryTable()
    {
        $route = Route::all();

        if(request()->ajax()) {
            // $query = Route::select('*');

         $consumer = Consumer::select('*')->where('consumer_type','11')->pluck('route_id');
        $query = Route::select('*')->where('sub',null)->where('combine',null)->whereIn('id',$consumer);

            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->editColumn('read_date', function($query) {

               $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($reading) {

                    return date('Y-m-d', strtotime( $log->created_at));
                }
                else
                {
                    return 'Not Read Yet';
                }

            })
            ->editColumn('total_meter', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               return count($consumer);

            })

            ->editColumn('exceed_int', function($query) {

                $consumer = Consumer::where('route_id',$query->id)->pluck('id');

                $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

                $readingslatest = Reading::whereIn('meter_id',$meters)->orderBy('created_at', 'desc')->first();
                $readingslast = Reading::whereIn('meter_id',$meters)->orderBy('created_at', 'asc')->first();
                // dd($readingslatest);

                if ($readingslatest == null || $readingslast == null) {
                    return 'Not Available';
                }
                else
                {

                    $startTime = new DateTime($readingslatest->created_at);
                    $endTime = new DateTime($readingslast->created_at);


                    $interval = date_diff($startTime, $endTime);

                    return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';
                }



            })

            ->editColumn('everage_exceed_int', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

                $meters = Meter::whereIn('consumer_id',$consumer)->pluck('id');

                $readingslatest = Reading::whereIn('meter_id',$meters)->orderBy('created_at', 'desc')->first();
                $readingslast = Reading::whereIn('meter_id',$meters)->orderBy('created_at', 'asc')->first();
                // dd($readingslatest);

                if ($readingslatest == null || $readingslast == null) {
                    return 'Not Available';
                }
                else
                {

                    $startTime = new DateTime($readingslatest->created_at);
                    $endTime = new DateTime($readingslast->created_at);


                    $interval = date_diff($startTime,$endTime);

                    return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';
                }

            })


            ->toJson();
        }
    }

    public function loginLogoutResident()
    {
        return view('report.loginLogoutResident');
    }

    public function billingDetailsByAmountResident()
    {
        return view('report.billingDetailsByAmountResident');
    }

    public function billingDetailsByRouteResident()
    {
        return view('report.billingDetailsByRouteResident');
    }

    public function billingDetailsSummaryByRouteResident()
    {
        return view('report.billingDetailsSummaryByRouteResident');
    }

    public function billingDetailsByRouteGMSBResident()
    {
        return view('report.billingDetailsByRouteGMSBResident');
    }


    public function loginLogoutResidentTable()
    {
        $login = 'login';
        if(request()->ajax()) {
        $user = User::where('type','=',4)->pluck('id');
            // $query = Route::select('*');
        $query = Log::Where('activity','like', '%'.$login.'%')->whereIn('user',$user);
            return datatables()->eloquent($query)
            ->addIndexColumn()

            ->editColumn('name', function($query) {
                  $user = User::find($query->user);
                  if ($user) {
                    return $user->full_name;
                  }

            })
            ->editColumn('read', function($query) {
                $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();
                // $reading = Reading::where('meter_reader_id',$query->user)->get();

                // $reading = Reading::get();

                if ($reading) {
                    return $reading->count();
                }
                else
                {
                    return 0;
                }


            })


            ->editColumn('noaccess', function($query) {


                $noaccess = Reading::where('meter_reader_id',$query->user)->where('id',$query->id)->where('issue_code_id',3)->get();

                return count($noaccess);

            })

            ->editColumn('unread', function($query) {

            $readings = Reading::select('*')->leftjoin('meters' , 'readings.meter_id', '=', 'meters.id')->leftjoin('consumers', 'consumers.id', '=', 'meters.consumer_id')->leftjoin('handheld_routes','handheld_routes.route_id', '=', 'consumers.route_id')->get();

            $reading = Reading::get();

                return count($readings) - count($reading);

            })

            ->editColumn('time', function($query) {

                $logout = 'logout';

                $log = Log::Where('activity','like', '%'.$logout.'%')->where('user',$query->user)->first();

                if ($log) {

                    $startTime = new DateTime($query->created_at);
                    $endTime = new DateTime('2021-04-01 01:30');

                    $interval = date_diff($startTime, $endTime);

                    // dd($interval);

                    return $interval->h. ' Hour(s) ' . $interval->i .' Minute(s) '. $interval->s .' Second(s)';

                }
                else
                {
                    return 'User Not Logout';
                }
            })

             ->editColumn('logout', function($query) {

                $logout = 'logout';

                $log = Log::Where('activity','like', '%'.$logout.'%')->where('user',$query->user)->first();

                if ($log) {

                    return $log->created_at;
                }
                else
                {
                    return 'User Not Logout';
                }

            })

            ->toJson();
        }
    }
    public function billingDetailsByAmountResidentTable()
    {
         if(request()->ajax()) {
            // $query = Route::select('*');
        $query = Consumer::select('*')->where('consumer_type','01');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('route', function($query) {

                $route = Route::Where('id', $query->route_id)->first();

                if ($route) {

                    return $route->route;
                }
                else
                {
                    return 'User Not Logout';
                }

            })
            ->editColumn('address', function($query) {
                return $query->consumer_address_1.$query->consumer_address_2;
            })
            ->editColumn('meter_number', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->meter_number;
            })

            ->editColumn('dial', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->dial_length;
            })
            ->editColumn('last_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->last_reading_date;
            })
            ->editColumn('current_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  date('Y-m-d', strtotime( $reading->created_at));
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
            ->editColumn('previous_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();

                if ($meter) {

                    return  $meter->last_reading;
                }
                else
                {
                    return 'Meter Not Found';
                }

            })
             ->editColumn('current_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  $reading->reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
              ->editColumn('current_consumption', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {


                    return  $reading->reading-$meter->last_reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
              ->editColumn('mmbtu', function($query) {

                        $meter = Meter::where('consumer_id', $query->id)->first();
                        $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();
                        if ($reading) {
                            return  $reading->reading;
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                })
              ->editColumn('cutoff_consumption', function($query) {

               return 0;

                })
               ->editColumn('bill_amount', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  $bill->current_bill;
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('timestamp', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  date('Y-m-d', strtotime( $bill->created_at));
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('mrid', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  $bill->meter_reader_id;
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('remark', function($query) {

               return '00';

                })
            ->toJson();
        }

    }
    public function billingDetailsByRouteResidentTable()
    {
         if(request()->ajax()) {
            // $query = Route::select('*');
            $query = Consumer::select('*')->where('consumer_type','01');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('route', function($query) {

                $route = Route::Where('id', $query->route_id)->first();

                if ($route) {

                    return $route->route;
                }
                else
                {
                    return 'User Not Logout';
                }

            })
            ->editColumn('address', function($query) {
                return $query->consumer_address_1.$query->consumer_address_2;
            })
            ->editColumn('meter_number', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->meter_number;
            })

            ->editColumn('dial', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->dial_length;
            })
            ->editColumn('last_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->last_reading_date;
            })
            ->editColumn('current_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  date('Y-m-d', strtotime( $reading->created_at));
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
            ->editColumn('previous_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();

                if ($meter) {

                    return  $meter->last_reading;
                }
                else
                {
                    return 'Meter Not Found';
                }

            })
             ->editColumn('current_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  $reading->reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
              ->editColumn('current_consumption', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {


                    return  $reading->reading-$meter->last_reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
              ->editColumn('mmbtu', function($query) {

                        $meter = Meter::where('consumer_id', $query->id)->first();
                        $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();
                        if ($reading) {
                            return  $reading->reading;
                        }
                        else
                        {
                            return 'Not Yet Read';
                        }
                })
              ->editColumn('cutoff_consumption', function($query) {

               return 0;

                })
               ->editColumn('bill_amount', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  $bill->current_bill;
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('gst', function($query) {
                    return 0;
                })
               ->editColumn('gcpt', function($query) {
                    return 0;
                })
               ->editColumn('timestamp', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  date('Y-m-d', strtotime( $bill->created_at));
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('mrid', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  $bill->meter_reader_id;
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('remark', function($query) {

               return '00';

                })
            ->toJson();
        }

    }
    public function billingDetailsSummaryByRouteResidentTable()
    {
         if(request()->ajax()) {
            // $query = Route::select('*');
             $consumer = Consumer::select('*')->where('consumer_type','01')->pluck('route_id');
        $query = Route::select('*')->where('sub',null)->where('combine',null)->whereIn('id',$consumer);
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('route', function($query) {
                    return $query->route;
                })
            ->editColumn('read_date', function($query) {

               $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($reading) {

                    return date('Y-m-d', strtotime( $log->created_at));
                }
                else
                {
                    return 'Not Read Yet';
                }

            })
            ->editColumn('total_acc', function($query) {

               $consumer = Consumer::where('route_id',$query->id)->pluck('id');

               return count($consumer);

            })
            ->editColumn('read', function($query) {
                $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();
                // $reading = Reading::where('meter_reader_id',$query->user)->get();

                // $reading = Reading::get();

                if ($reading) {
                    return count($reading);
                }
                else
                {
                    return 0;
                }
            })

            ->editColumn('noaccess', function($query) {

                $noaccess = Reading::where('meter_reader_id',$query->user)->where('id',$query->id)->where('issue_code_id',3)->get();

                return count($noaccess);

            })

            ->editColumn('unread', function($query) {

             $consumer = Consumer::where('route_id',$query->id)->pluck('id');

             $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }
                $reading = Reading::where('meter_reader_id',$query->user)->Where('created_at','like', '%'.$date_check.'%')->first();
                // $reading = Reading::where('meter_reader_id',$query->user)->get();

                // $reading = Reading::get();

                if ($reading) {
                    return count($consumer) - count($reading);
                }
                else
                {
                    return count($consumer);
                }

            })
            ->editColumn('consumption', function($query) {

                $date_check = date('Y-m');
                if(date('d') < 22) {
                    $date_check = date('Y-m', strtotime('-28 days'));
                } else {
                    $date_check = date('Y-m');
                }

                $consumer = Consumer::where('route_id',$query->id)->pluck('id');

                $meter = Meter::wherein('consumer_id',$consumer)->pluck('id');

                $readings = Reading::whereIn('meter_id',$meter)->Where('created_at','like', '%'.$date_check.'%')->get();

                if ($readings) {
                    // return count($noaccess);
                    $total = 0;
                    for ($i=0; $i < count($readings); $i++) {
                        $total += $readings[$i]->reading;
                    }
                    return $total;
                }
                else
                {
                    return 0;
                }

            })
             ->editColumn('mmbtu', function($query) {
                return 0;
            })
            ->editColumn('amount', function($query) {
                $consumer = Consumer::where('route_id',$query->id)->pluck('id');

                $bill = Bill::WhereIn('consumer_id',$consumer)->get();

                if ($bill) {
                    $total = 0;
                    for ($i=0; $i < count($bill); $i++) {
                        $total += $bill[$i]->bill_amount;
                    }
                    return $total;
                }

            })
            ->editColumn('gst', function($query) {
                return 0;
            })
            ->editColumn('gcpt', function($query) {
                return 0;
            })

            ->toJson();
        }

    }
    public function billingDetailsByRouteGMSBResidentTable()
    {
        if(request()->ajax()) {
            // $query = Route::select('*');
            $query = Consumer::select('*')->where('consumer_type','01');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('route', function($query) {

                $route = Route::Where('id', $query->route_id)->first();

                if ($route) {

                    return $route->route;
                }
                else
                {
                    return 'User Not Logout';
                }

            })
            ->editColumn('address', function($query) {
                return $query->consumer_address_1.$query->consumer_address_2;
            })
            ->editColumn('meter_number', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->meter_number;
            })
            ->editColumn('last_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                return $meter->last_reading_date;
            })
            ->editColumn('current_reading_date', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  date('Y-m-d', strtotime( $reading->created_at));
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
            ->editColumn('previous_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();

                if ($meter) {

                    return  $meter->last_reading;
                }
                else
                {
                    return 'Meter Not Found';
                }

            })
             ->editColumn('current_reading', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {

                    return  $reading->reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
              ->editColumn('current_consumption', function($query) {

                $meter = Meter::where('consumer_id', $query->id)->first();
                $reading = Reading::where('meter_id',$meter->meter_id)->orderBy('created_at', 'desc')->first();

                if ($reading) {


                    return  $reading->reading-$meter->last_reading;
                }
                else
                {
                    return 'Not Yet Read';
                }

            })
               ->editColumn('bill_amount', function($query) {

                $date_check = date('Y-m');
                    if(date('d') < 22) {
                        $date_check = date('Y-m', strtotime('-28 days'));
                    } else {
                        $date_check = date('Y-m');
                    }

                $bill = Bill::where('consumer_id', $query->id)->Where('created_at','like', '%'.$date_check.'%')->first();

                if ($bill) {

                    return  $bill->current_bill;
                }
                else
                {
                    return 'Not Yet Billed For This Month';
                }

                })
               ->editColumn('gst', function($query) {
                    return 0;
                })
               ->editColumn('cf_value', function($query) {
                 $meter = Meter::where('consumer_id', $query->id)->first();

                return $meter->cf_factor / 10000;

                })
            ->toJson();
        }

    }
}
