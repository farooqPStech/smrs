<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Handheld;
use App\Models\Log;
use App\Models\Branch;
use Illuminate\Support\Facades\Redirect;



class HandheldController extends Controller
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
        $handhelds = Handheld::all();

        for ($i=0; $i < count($handhelds); $i++) {

            $branch = Branch::find($handhelds[$i]->branch_id);

            $handhelds[$i]->branch = $branch;
        }

        $items = array(
            'handhelds' => $handhelds,
        );
        return view('handheld.list')->with($items);
    }
     public function addHandheld()
    {
         $branches = Branch::all();

          $items = array(
            'branches' => $branches,
        );
        return view('handheld.addHandheld')->with($items);
    }

    public function addNewHandheld(Request $request)
    {
        $request->validate([
            'uuid'=> 'required',
            'type' => 'required',
            'branch_id'=> 'required',
            'brand' => 'required',
            'status'=> 'required',
            'label' => 'required',
        ]);

        $handheld = new Handheld();
        $handheld->uuid = $request->uuid;
        $handheld->label = $request->label;
        $handheld->type = $request->type;
        $handheld->brand = $request->brand;
        $handheld->branch_id = $request->branch_id;
        $handheld->status = $request->status;
        $bool=$handheld->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Handheld At :".$handheld->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'New Handheld Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Handheld Fail to Add!');
            return Redirect::back();
        }
    }

    public function editHandheld($id)
    {
        $handheld = Handheld::find($id);

        $branches = Branch::all();


        $items = array(
            'handheld' => $handheld,
            'branches' => $branches,
        );
        return view('handheld.editHandheld')->with($items);
    }
    public function updateHandheld(Request $request)
    {
        $handheld = Handheld::find($request->id);
        $handheld->uuid = $request->uuid;
        $handheld->label = $request->label;
        $handheld->meter_reader = $request->meter_reader;
        $handheld->type = $request->type;
        $handheld->brand = $request->brand;
        $handheld->branch_id = $request->branch_id;
        $handheld->status = $request->status;
        $bool=$handheld->save();

        if($bool)
        {

            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Handheld Update At ".$handheld->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();


            \Session::flash('status', 'Handheld Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Handheld Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeHandheld($id)
    {
        $handheld = Handheld::find($id);
        $handheld->status = 0;
        $handheld->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Handheld Deactivated At: " . $handheld;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'handheld Deleted!');
        return Redirect::back();
    }
}
