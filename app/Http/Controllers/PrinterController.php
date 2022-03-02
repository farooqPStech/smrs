<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use App\Models\Handheld;
use App\Models\Log;
use App\Models\Branch;
use Illuminate\Support\Facades\Redirect;



class PrinterController extends Controller
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
        $printers = Printer::where('status',1)->get();

        for ($i=0; $i < count($printers); $i++) { 

            $branch = Branch::find($printers[$i]->branch_id);

            $printers[$i]->branch = $branch;
        }

        $items = array(
            'printers' => $printers, 
        );
        return view('printer.list')->with($items);
    }
     public function addPrinter()
    {
         $branches = Branch::all();

          $items = array(
            'branches' => $branches, 
        );
        return view('printer.addPrinter')->with($items);
    }

    public function addNewPrinter(Request $request)
    {
        $printer = new Printer();
        $printer->mac_address = $request->mac;
        $printer->label = $request->label;
        $printer->type = $request->type;
        $printer->brand = $request->brand;
        $printer->branch_id = $request->branch_id;
        $printer->status = $request->status;
        $bool=$printer->save();
        
        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Printer At :".$printer->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'New Printer Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Printer Fail to Add!');
            return Redirect::back();
        }
    }

    public function editPrinter($id)
    {
        $printer = Printer::find($id);

        $branches = Branch::all();


        $items = array(
            'printer' => $printer,
            'branches' => $branches, 
        );
        return view('printer.editPrinter')->with($items);
    }
    public function updatePrinter(Request $request)
    {
        $handheld = Printer::find($request->id);
        $handheld->mac_address = $request->mac;
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
            $new->activity = "Printer Update At ".$handheld->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();


            \Session::flash('status', 'Printer Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Printer Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removePrinter($id)
    {
        $handheld = Printer::find($id);
        $handheld->status = 0;
        $handheld->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Printer Deactivated At: " . $handheld;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Printer Deleted!');
        return Redirect::back();
    }
}
