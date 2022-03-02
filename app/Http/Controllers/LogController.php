<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Consumer;
use App\User;


class LogController extends Controller
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
        // $all_user = User::all();

        if(request()->ajax()) {
            $query = Log::select('*');
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
            return $request->created_at->format('d-M-Y'); // human readable format
            })
            // ->addColumn('status', function ($query) {
            //     return $query->status_str();
            //   })
            // ->addColumn('address', function ($query) {
                // return $query->unit->address1 . ' ' . $query->unit->address2;    
            // })
            ->toJson();
        }

        
        return view('log.list');
    }
    
    public function web()
    {
        // $all_user = User::all();

        if(request()->ajax()) {

            $user = User::where('type','!=',4)->pluck('id');

            $query = Log::select('*')->whereIn('user',$user);
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
            return $request->created_at->format('d-M-Y'); // human readable format
            })
             ->editColumn('user', function($query) {

               $user = User::where('id',$query->user)->first();

               return $user->full_name.' ('.$user->id.')';

            })
            // ->addColumn('status', function ($query) {
            //     return $query->status_str();
            //   })
            // ->addColumn('address', function ($query) {
                // return $query->unit->address1 . ' ' . $query->unit->address2;    
            // })
            ->toJson();
        }

        
        return view('log.list');
    }

    public function handheld()
    {
        // $all_user = User::all();

        if(request()->ajax()) {
            $user = User::where('type',4)->pluck('id');
            $query = Log::select('*')->whereIn('user',$user);
            return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
            return $request->created_at->format('d-M-Y'); // human readable format
            })
             ->editColumn('user', function($query) {

               $user = User::where('id',$query->user)->first();

               return $user->full_name.' ('.$user->id.')';

            })
            // ->addColumn('status', function ($query) {
            //     return $query->status_str();
            //   })
            // ->addColumn('address', function ($query) {
                // return $query->unit->address1 . ' ' . $query->unit->address2;    
            // })
            ->toJson();
        }

        
        return view('log.list');
    }
}
