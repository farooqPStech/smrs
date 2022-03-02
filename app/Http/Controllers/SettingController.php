<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyInformations;
use App\Models\Branch;
use App\Models\Log;
use App\Models\IssueCode;
use App\Models\ObstacleCode;
use App\Models\MeterLocation;
use App\Models\AppVersion;
use App\Models\DueDate;
use App\Models\BillNote;
use App\Models\Price;
use App\Models\GCPT;
use App\Models\Highlow;
use App\Models\ConsumptionVariant;
use App\Models\File;
use App\Models\Billing;
use App\Models\Printable;
use App\Models\RejectCode;
use App\Models\Consumer;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
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
    public function info()
    {
        $info = CompanyInformations::find(1);
        $info2 = CompanyInformations::find(2);
        $info3 = CompanyInformations::find(3);

        $items = array(
            'info' => $info,
            'info2' => $info2,
            'info3' => $info3,
        );
        return view('setting.info')->with($items);
    }
    public function infoUpdate(Request $request)
    {
        $info = CompanyInformations::find($request->id);

        $info->name = $request->name;
        $info->address = $request->address;
        $info->registration_number = $request->reg;
        $info->gst_number = $request->gst;
        $info->phone_number = $request->phone;
        $info->fax_number = $request->fax;
        $info->updated_by = auth()->user()->id;

        $bool=$info->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Company Info Update At ".$info->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            \Session::flash('status', 'Company Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Company Info Fail to Update!');
            return Redirect::back();
        }

    }

    public function branch()
    {
        $branches = Branch::all();

        for ($i=0; $i < count($branches); $i++) {

            $new = User::where('id',$branches[$i]->updated_by)->first();
            $branches[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'branches' => $branches,
        );
        return view('setting.branch')->with($items);
    }

    public function addBranch()
    {
        return view('setting.addBranch');
    }

    public function addNewBranch(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'name' => 'required',
        ]);

        $branch = new Branch();
        $branch->code = $request->code;
        $branch->name = $request->name;
        $branch->updated_by = auth()->user()->id;
        $bool = $branch->save();

        if($bool)
        {

            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new branch At :".$branch->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();


            \Session::flash('status', 'Branch Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Branch Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editBranch($id)
    {
        $branch = Branch::find($id);

        $items = array(
            'branch' => $branch,
        );
        return view('setting.editBranch')->with($items);
    }
    public function updateBranch(Request $request)
    {
        $branch = Branch::find($request->id);
        $branch->name = $request->name;
        $branch->code = $request->code;
        $branch->updated_by = auth()->user()->id;
        $bool=$branch->save();

        if($bool)
        {

            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Branch Update At ".$branch->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();


            \Session::flash('status', 'Branch Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Branch Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeBranch($id)
    {
        $branch = Branch::find($id);
        $branch->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Branch Deleted At: " . $branch;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Branch Deleted!');
        return Redirect::back();
    }
    public function issueCode()
    {
        $issues = IssueCode::all();

        for ($i=0; $i < count($issues); $i++) {

            $new = User::where('id',$issues[$i]->updated_by)->first();
            $issues[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'issues' => $issues,
        );
        return view('setting.issueCode')->with($items);
    }
    public function addIssue()
    {
        return view('setting.addIssue');
    }

    public function addNewIssue(Request $request)
    {
        $request->validate([
            'issue'=> 'required',
            'bill'=> 'required',
            'description' => 'required',
        ]);

        $issue = new IssueCode();
        $issue->code_number = $request->issue;
        $issue->description = $request->description;
        $issue->bill_code = $request->bill;
        $issue->updated_by = auth()->user()->id;
        $bool = $issue->save();

        if($bool)
        {

            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Issue At :".$issue->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();


            \Session::flash('status', 'Issue Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Issue Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editIssue($id)
    {
        $issue = IssueCode::find($id);

        $items = array(
            'issue' => $issue,
        );
        return view('setting.editIssue')->with($items);
    }
    public function updateIssue(Request $request)
    {
        $issue = IssueCode::find($request->id);
        $issue->code_number = $request->issue;
        $issue->description = $request->description;
        $issue->bill_code = $request->bill;
        $issue->updated_by = auth()->user()->id;
        $bool=$issue->save();

        if($bool)
        {

            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Issue Update At ".$issue->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Issue Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Issue Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeIssue($id)
    {
        $issue = IssueCode::find($id);
        $issue->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Issue Deleted At: " . $issue;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Issue Deleted!');
        return Redirect::back();
    }

    public function obstacleCode()
    {
        $obstacles = ObstacleCode::all();

        for ($i=0; $i < count($obstacles); $i++) {

            $new = User::where('id',$obstacles[$i]->updated_by)->first();
            $obstacles[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'obstacles' => $obstacles,
        );
        return view('setting.obstacleCode')->with($items);
    }

    public function addObstacle()
    {
        return view('setting.addObstacle');
    }

    public function addNewObstacle(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'description' => 'required',
        ]);

        $obstacle = new ObstacleCode();
        $obstacle->code_number = $request->code;
        $obstacle->description = $request->description;
        $obstacle->updated_by = auth()->user()->id;
        $bool = $obstacle->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Obstacle Code At :".$obstacle->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Obstacle Code Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Obstacle Code Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editObstacle($id)
    {
        $obstacle = ObstacleCode::find($id);

        $items = array(
            'obstacle' => $obstacle,
        );
        return view('setting.editObstacle')->with($items);
    }
    public function updateObstacle(Request $request)
    {
        $obstacle = ObstacleCode::find($request->id);
        $obstacle->code_number = $request->code;
        $obstacle->description = $request->description;
        $obstacle->updated_by = auth()->user()->id;
        $bool = $obstacle->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Obstacle Code Update At ".$obstacle->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Obstacle Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Obstacle Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeObstacle($id)
    {
        $obstacle = ObstacleCode::find($id);
        $obstacle->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Issue Deleted At: " . $obstacle;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Issue Deleted!');
        return Redirect::back();
    }

    //start
    public function rejectCode()
    {
        $rejects = RejectCode::all();

        for ($i=0; $i < count($rejects); $i++) {

            $new = User::where('id',$rejects[$i]->updated_by)->first();
            $rejects[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'rejects' => $rejects,
        );
        return view('setting.rejectCode')->with($items);
    }

    public function addReject()
    {
        return view('setting.addReject');
    }

    public function addNewReject(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'description' => 'required',
        ]);

        $reject = new RejectCode();
        $reject->code_number = $request->code;
        $reject->description = $request->description;
        $reject->updated_by = auth()->user()->id;
        $bool = $reject->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new reject Code At :".$reject->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Reject Code Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Reject Code Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editReject($id)
    {
        $reject = RejectCode::find($id);

        $items = array(
            'reject' => $reject,
        );
        return view('setting.editReject')->with($items);
    }
    public function updateReject(Request $request)
    {
        $reject = RejectCode::find($request->id);
        $reject->code_number = $request->code;
        $reject->description = $request->description;
        $reject->updated_by = auth()->user()->id;
        $bool = $reject->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Reject Code Update At ".$reject->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Rbstacle Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Rbstacle Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeReject($id)
    {
        $reject = RejectCode::find($id);
        $reject->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Reject Code Deleted At: " . $reject;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Reject Code Deleted!');
        return Redirect::back();
    }
    //end

    public function meterLocation()
    {
         $meterLocation = MeterLocation::all();

        for ($i=0; $i < count($meterLocation); $i++) {

            $new = User::where('id',$meterLocation[$i]->updated_by)->first();
            $meterLocation[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'meterLocation' => $meterLocation,
        );
        return view('setting.meterLocation')->with($items);
    }
    public function addMeterLocation()
    {
        return view('setting.addMeterLocation');
    }
    public function addNewMeterLocation(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'description' => 'required',
        ]);

        $meterLocation = new MeterLocation();
        $meterLocation->code_number = $request->code;
        $meterLocation->description = $request->description;
        $meterLocation->updated_by = auth()->user()->id;
        $bool = $meterLocation->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Meter Location Code At :".$meterLocation->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Meter Location Code Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Meter Location Code Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editMeterLocation($id)
    {
        $obstacle = MeterLocation::find($id);

        $items = array(
            'obstacle' => $obstacle,
        );
        return view('setting.editMeterLocation')->with($items);
    }
    public function updateMeterLocation(Request $request)
    {
        $meterLocation = MeterLocation::find($request->id);
        $meterLocation->code_number = $request->code;
        $meterLocation->description = $request->description;
        $meterLocation->updated_by = auth()->user()->id;
        $bool = $meterLocation->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Obstacle Code Update At ".$meterLocation->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Meter Location Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Meter Location Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeMeterLocation($id)
    {
        $meterLocation = MeterLocation::find($id);
        $meterLocation->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Issue Deleted At: " . $meterLocation;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Meter Location Code Deleted!');
        return Redirect::back();
    }
    public function highlow()
    {
        $highlow = Highlow::all();

        for ($i=0; $i < count($highlow); $i++) {

            $new = User::where('id',$highlow[$i]->updated_by)->first();
            $highlow[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'highlow' => $highlow,
        );
        return view('setting.highlow')->with($items);
    }
    public function addHighlow()
    {
        return view('setting.addHighlow');
    }
    public function addNewHighlow(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'high' => 'required',
            'low' => 'required',
        ]);

        $highlow = new Highlow();
        $highlow->tariff_code = $request->code;
        $highlow->high_consumption = $request->high;
        $highlow->low_consumption = $request->low;
        $highlow->updated_by = auth()->user()->id;
        $bool = $highlow->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Highlow Code At :".$highlow->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Highlow Code Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Highlow Code Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editHighlow($id)
    {
        $highlow = Highlow::find($id);

        $items = array(
            'highlow' => $highlow,
        );
        return view('setting.editHighLow')->with($items);
    }
    public function updateHighlow(Request $request)
    {
        $highlow = Highlow::find($request->id);
        $highlow->tariff_code = $request->code;
        $highlow->high_consumption = $request->high;
        $highlow->low_consumption = $request->low;
        $highlow->updated_by = auth()->user()->id;
        $bool = $highlow->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "HighLow Code Update At ".$highlow->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Highlow Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Highlow Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeHighlow($id)
    {
        $highlow = Highlow::find($id);
        $highlow->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Highlow Deleted At: " . $highlow;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Highlow Code Deleted!');
        return Redirect::back();
    }
    public function price()
    {
        $prices = Price::all();

        for ($i=0; $i < count($prices); $i++) {

            $new = User::where('id',$prices[$i]->updated_by)->first();
            if($new)
            {
                $prices[$i]->updated_by = $new->full_name;
            }
            else
            {
                $prices[$i]->updated_by = 'Not Available';
            }
        }

        $items = array(
            'prices' => $prices,
        );
        return view('setting.price')->with($items);
    }
    public function addPrice()
    {
        return view('setting.addPrice');
    }
    public function addNewPrice(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'min_charge' => 'required',
            'min_quantity' => 'required',
            'min_consumption' => 'required',
            'rate' => 'required',
            'gstrate'=> 'required',
            'multiplier' => 'required',
            'effective_date' => 'required',
            'max_consumption' => 'required',
        ]);

        $price = new Price();
        $price->code = $request->code;
        $price->min_charge = $request->min_charge;
        $price->min_quantity = $request->min_quantity;
        $price->min_consumption = $request->min_consumption;
        $price->rate = $request->rate;
        $price->gst_rate = $request->gstrate;
        $price->multiplier = $request->multiplier;
        $price->effective_date = $request->effective_date;
        $price->max_consumption = $request->max_consumption;
        $price->updated_by = auth()->user()->id;
        $bool = $price->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Price At :".$price->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Price Info Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Price Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function editPrice($id)
    {
        $price = Price::find($id);

        $items = array(
            'price' => $price,
        );
        return view('setting.editPrice')->with($items);
    }
    public function updatePrice(Request $request)
    {
        $price = Price::find($request->id);
        $price->code = $request->code;
        $price->min_charge = $request->min_charge;
        $price->min_quantity = $request->min_quantity;
        $price->min_consumption = $request->min_consumption;
        $price->rate = $request->rate;
        $price->gst_rate = $request->gstrate;
        $price->multiplier = $request->multiplier;
        $price->effective_date = $request->effective_date;
        $price->max_consumption = $request->max_consumption;
        $price->updated_by = auth()->user()->id;
        $bool = $price->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Price Update At ".$price->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Price Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Price Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removePrice($id)
    {
        $price = Price::find($id);
        $price->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Price Deleted At: " . $price;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Price Deleted!');
        return Redirect::back();
    }
    public function billing()
    {
        $billing = Billing::all();

        for ($i=0; $i < count($billing); $i++) {

            $new = User::where('id',$billing[$i]->updated_by)->first();
            $billing[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'billing' => $billing,
        );
        return view('setting.billing')->with($items);
    }

    public function editBilling($id)
    {
        $billing = Billing::find($id);

        $items = array(
            'billing' => $billing,
        );
        return view('setting.editBilling')->with($items);
    }
    public function updateBilling(Request $request)
    {
        $billing = Billing::find($request->id);
        $billing->month_number = $request->month_number;
        $billing->month_name = $request->month_name;
        $billing->number_of_day = $request->number_of_day;
        $billing->updated_by = auth()->user()->id;
        $bool = $billing->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Billing Update At ".$billing->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Billing Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Billing Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeBilling($id)
    {
        $billing = Billing::find($id);
        $billing->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Billing Deleted At: " . $billing;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Billing Deleted!');
        return Redirect::back();
    }
    public function gcpt()
    {
        $gcpt = GCPT::all();

        for ($i=0; $i < count($gcpt); $i++) {

            $new = User::where('id',$gcpt[$i]->updated_by)->first();
            $gcpt[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'gcpt' => $gcpt,
        );
        return view('setting.gcpt')->with($items);
    }

    public function addGCPT()
    {
        return view('setting.addGCPT');
    }
    public function addNewGCPT(Request $request)
    {
        $request->validate([
            'code'=> 'required',
            'rate' => 'required',
            'effective_date'=> 'required',
        ]);

        $gcpt = new GCPT();
        $gcpt->code = $request->code;
        $gcpt->effective_date = $request->effective_date;
        $gcpt->rate = $request->rate;
        $gcpt->updated_by = auth()->user()->id;
        $bool = $gcpt->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Price At :".$gcpt->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'GCPT Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'GCPT Fail to Update!');
            return Redirect::back();
        }
    }

    public function editGCPT($id)
    {
        $gcpt = GCPT::find($id);

        $items = array(
            'gcpt' => $gcpt,
        );
        return view('setting.editGCPT')->with($items);
    }
    public function updateGCPT(Request $request)
    {
        $gcpt = GCPT::find($request->id);
        $gcpt->code = $request->code;
        $gcpt->effective_date = $request->effective_date;
        $gcpt->rate = $request->rate;
        $gcpt->updated_by = auth()->user()->id;
        $bool = $gcpt->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Price Update At ".$gcpt->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Price Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Price Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function removeGCPT($id)
    {
        $gcpt = GCPT::find($id);
        $gcpt->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "GCPT Deleted At: " . $gcpt;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'GCPT Deleted!');
        return Redirect::back();
    }
    public function AppVersion()
    {
        $app = AppVersion::all();

        for ($i=0; $i < count($app); $i++) {

            $new = User::where('id',$app[$i]->updated_by)->first();
            $app[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'app' => $app,
        );
        return view('setting.app')->with($items);
    }
    public function addApp()
    {
        return view('setting.addApp');
    }
    public function addNewApp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file-upload' => 'required|mimes:apk',
            'version' => 'required',
            'name' => 'required',
            // 'url' => 'required',
        ]);

        if ($validator->fails()) {
            \Session::flash('status', $validator->messages()->first());
            return Redirect::back();
        }

        $uploadedFile = $request->file('file-upload');
        $filename = $uploadedFile->getClientOriginalName();
        $destinationPath = 'apk/';
        $uploadedFile->move($destinationPath,$uploadedFile->getClientOriginalName());


        $app = new AppVersion();
        $app->version_number = $request->version;
        $app->name = $request->name;
        $app->url = '/apk/'.$request->name.'.apk';
        $app->comment = $request->comment;
        $app->status = 0;
        $app->updated_by = auth()->user()->id;
        $bool = $app->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new app version At :".$app->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'App Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'App Fail to Upload!');
            return Redirect::back();
        }
    }
    public function editApp($id)
    {
        $app = AppVersion::find($id);

        $items = array(
            'app' => $app,
        );
        return view('setting.editApp')->with($items);
    }

    public function updateApp(Request $request)
    {
        $app = AppVersion::find($request->id);

        $version = $request->version;
        $name = $request->name;
        $comment = $request->comment;


        $app->version_number = $version;
        $app->name = $name;
        $app->comment = $comment;
        $app->save();

         \Session::flash('status', 'Details Updated!');
        return Redirect::back();
    }

    public function setActive($id)
    {

        $app = DB::table('app_versions')->update(['status' => 0]);

        $app = AppVersion::find($id);

        $app->status = 1;

        $app->save();

        \Session::flash('status', 'App Activated!');
        return Redirect::back();
    }

    public function dueDate()
    {
        $dueDate = DueDate::find(1);

        $items = array(
            'dueDate' => $dueDate,
        );
        return view('setting.dueDate')->with($items);
    }

    public function updateDueDate(Request $request)
    {
        $info = DueDate::find($request->id);

        $info->number_of_day = $request->no;
        $info->updated_by = auth()->user()->id;

        $bool=$info->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Due Date Update At ".$info->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            \Session::flash('status', 'Due Date Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Due Date Info Fail to Update!');
            return Redirect::back();
        }
    }

    public function billNote()
    {
        $app = BillNote::all();

        for ($i=0; $i < count($app); $i++) {

            $new = User::where('id',$app[$i]->updated_by)->first();
            $app[$i]->updated_by = $new->full_name;
        }

        $items = array(
            'app' => $app,
        );
        return view('setting.billNote')->with($items);
    }
    public function addBillNote()
    {
        return view('setting.addBillNote');
    }
    public function addNewBillNote(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'note' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            \Session::flash('status', $validator->messages()->first());
            return Redirect::back();
        }

        $app = new BillNote();
        $app->bill_notes = $request->note;
        $app->comment = $request->comment;
        $app->status = $request->status;
        $app->updated_by = auth()->user()->id;
        $bool = $app->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Add new Note At :".$app->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Note Added!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Note Fail to Upload!');
            return Redirect::back();
        }
    }
    public function editBillNote($id)
    {
        $app = BillNote::find($id);

        $items = array(
            'app' => $app,
        );
        return view('setting.editBillNote')->with($items);
    }

    public function updateBillNote(Request $request)
    {
        $app = BillNote::find($request->id);

        $note = $request->note;
        $status = $request->status;
        $comment = $request->comment;


        $app->bill_notes = $note;
        $app->status = $status;
        $app->comment = $comment;
        $app->updated_by = auth()->user()->id;
        $app->save();

         \Session::flash('status', 'Note Updated!');
        return Redirect::back();
    }

    public function setActiveBillNote($id)
    {

        $app = DB::table('bill_notes')->update(['status' => 0]);

        $app = BillNote::find($id);

        $app->status = 1;

        $app->save();

        \Session::flash('status', 'Notes Activated!');
        return Redirect::back();
    }

    public function file()
    {

        $file = File::find(1);

        $items = array(
            'file' => $file,
        );
        return view('setting.file')->with($items);
        // return Redirect::back();
    }

    public function updateFile(Request $request)
    {
        $file = File::find($request->id);

        $file->upload_ip = $request->upload_ip;
        $file->upload_filename = $request->upload_filename;
        $file->download_ip = $request->download_ip;
        $file->download_filename = $request->download_filename;
        $file->updated_by = auth()->user()->id;

        $bool=$file->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "IP Update At ".$file->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            \Session::flash('status', 'IP Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'IP Info Fail to Update!');
            return Redirect::back();
        }
    }
    public function printables()
    {

        $printable = Printable::find(1);

        $items = array(
            'printable' => $printable,
        );
        return view('setting.printables')->with($items);
        // return Redirect::back();
    }

    public function updatePrintables(Request $request)
    {
        $file = Printable::find($request->id);

        $file->status = $request->status;
        $file->updated_by = auth()->user()->id;

        $bool=$file->save();

        if($bool)
        {
            //log
            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Printable Update At ".$file->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
            \Session::flash('status', 'Printable Info Updated!');
            return Redirect::back();
        }
        else
        {
            \Session::flash('status', 'Printable Fail to Update!');
            return Redirect::back();
        }
    }

    public function consumptionVariant()
    {

        $cv = ConsumptionVariant::find(1);
        $cv2 = ConsumptionVariant::all();

        for ($i=0; $i < count($cv2); $i++) {
            $consumer = Consumer::find($cv2[$i]->consumer_id);
            if ($consumer) {
                $cv2[$i]->consumer = $consumer;
            }
        }

        $items = array(
            'cv' => $cv,
            'cv2' => $cv2,
        );
        return view('setting.consumptionVariant')->with($items);
    }

    public function updatecv(Request $request)
    {
        $cv = DB::table('consumption_variants')->update(['low' => $request->low,'high' => $request->high,'updated_by' => auth()->user()->id]);

        $cv = ConsumptionVariant::find($request->id);
        $cv->low = $request->low;
        $cv->high = $request->high;
        $cv->updated_by = auth()->user()->id;
        $cv->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Consumption Variant Updated by: ".auth()->user()->id;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Consumption Variant Updated!');
        return Redirect::back();
    }
}
