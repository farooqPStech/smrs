<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Consumer;
use App\Models\Reading as Readings;
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

class Reading extends Component
{
    public $branchCode;
    public $readings;
    public $consumerType =11;
    public $consumers ;
    public $branches ;
    public function render()
    {
        $this->branches = Branch::all();
        //$this->readings = Readings::all();
        switch (auth()->user()->type) {
            case 3:
            case 5:
                $branches = Branches::where('id', auth()->user()->branch_id)->get();
                break;
            default:
                break;
        }
        return view('livewire.reading', [
            'consumerType' => $this->consumerType,
            'branches' => $this->branches,
            //'readings' => $this->readings,
            //'branchCode' => 11
        ]);
    }

    public function branchCode($code){
        $date_check = date('Y-m');
        if(date('d') < 29) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }
        $this->branchCode = $code;
        $this->readings = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();
    }

}
