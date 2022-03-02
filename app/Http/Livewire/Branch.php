<?php

namespace App\Http\Livewire;

use App\Models\Branch as Branches;
use App\Models\Handheld;
use App\Models\Reading;
use App\Models\RejectCode;
use App\Models\User;
use function GuzzleHttp\Promise\all;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Branch extends Component
{
    use LivewireAlert;

    public $selectedReading = [];
    public $selectAll = false;
    public $bulkSelected = false;

    public $branchCode;
    public $branchName;
    public $readingsPair;
    public $readingsNoPair;
    public $branches;
    public $readingId = 0;
    public $rejectCodes;
    public $rejectReason;
    public $rejectCode;
    public $imageData;

    public $pair = 'other';

    public $isSuperUser = false;

    protected $listeners = [
        'confirmed',
    ];

    public function confirmed()
    {
        if ($this->readingId > 0) {
            if (!$this->isSuperUser) {
                $reading = Reading::where('id', $this->readingId)->update(['accepted1' => 2]);
            } else {
                $reading = Reading::where('id', $this->readingId)->update(['accepted2' => 2]);
            }

            return $this->render();
        }
        $this->readingId = 0;
    }

    public function render()
    {
        $this->rejectCodes = RejectCode::all();
        // $this->bulkSelected = count($this->selectedReading);
        // $this->branches = Branches::all();
        // $this->rejectCodes = RejectCode::all();
        // switch (auth()->user()->type) {
        //     case 3:
        //     case 5:
        //         $this->branches = Branches::where('id', auth()->user()->branch_id)->get();
        //         break;
        //     default:
        //         break;
        // }

        // $date_check = date('Y-m');
        // if (date('d') < 29) {
        //     $date_check = date('Y-m', strtotime('-28 days'));
        // } else {
        //     $date_check = date('Y-m');
        // }

        // $term = '%' . $date_check . '%';
        // $this->readings = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();
        $this->branches = Branches::all();
        switch (auth()->user()->type) {
            case 3:
            case 5:
                $this->branches = Branches::where('id', auth()->user()->branch_id)->get();
                break;
            default:
                break;
        }

        $date_check = date('Y-m');
        if (date('d') < 29) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        if (!$this->branchCode) {
            if ($this->branches) {
                $this->branchCode = $this->branches[0]->code;
                $this->branchName = $this->branches[0]->name;
            }
        }

        //$this->branchCode = 50;
        $term = '%' . $date_check . '%';

        /*$this->readingsNoPair = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();
        $this->readingsPair = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();*/

        $find1 = Reading::join('meters', function ($join) {
            $join->on('readings.meter_id', '=', 'meters.id')
                ->where(function($q) {
                    $q->where('meters.meter_type', 0);
                });
        })->get();
        $this->readingsNoPair = $find1;

        $find2 = Reading::join('meters', function ($join) {
            $join->on('readings.meter_id', '=', 'meters.id')
                ->where(function($q) {
                    $q->where('meters.meter_type', 1)->orWhere('meters.meter_type', 2);
                });
        })->get();
        $this->readingsPair = $find2;

        return view('livewire.branch');
    }

    public function verify($id, $isSuperUser = false)
    {
        $this->isSuperUser = $isSuperUser;
        $this->readingId = $id;
        $this->alert('question', 'Confirm to accept this reading?', [
            'position' => 'top',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'showDenyButton' => true,
            'denyButtonText' => 'No',
            'onConfirmed' => 'confirmed',
            'timer' => null,
        ]);
    }

    public function rejectSV($id)
    {
        $this->readingId = $id;
        $this->isSuperUser = false;
        $this->emit('rejectEvent');

    }

    public function rejectSU($id)
    {
        $this->readingId = $id;
        $this->isSuperUser = true;
        $this->emit('rejectEvent');
    }

    function rejectReading()
    {
        if (!$this->rejectCode) {
            $this->emit('hideModalEvent');
            $this->alert('error', 'Reject code required', [
                'position' => 'top',
            ]);
            return;
        }
        if ($this->readingId > 0) {
            if (!$this->isSuperUser) {
                Reading::where('id', $this->readingId)->update([
                    'accepted1' => 1,
                    'reason1' => $this->rejectReason,
                    'reject_code1' => $this->rejectCode,
                ]);
            } else {
                Reading::where('id', $this->readingId)->update([
                    'accepted2' => 1,
                    'reason2' => $this->rejectReason,
                    'reject_code2' => $this->rejectCode,
                ]);
            }

            $this->emit('hideModalEvent');
            $this->alert('question', 'Reading rejected', [
                'position' => 'top',
            ]);

            $reading = Reading::find($this->readingId);
            $rejectCode = RejectCode::find($this->rejectCode);

            $handheld = Handheld::where('uuid', $reading->meterReader->uuid)->first();
            if ($handheld->player_id) {
                \OneSignal::sendNotificationToUser(
                    'Reading Rejected Meter#:' . $reading->meter->meter_number . ': ' . $rejectCode->description . ', ' . $this->rejectReason . '',
                    $handheld->player_id,
                    $url = null,
                    $data = response()->json([
                        'success' => true,
                        'data' => $reading->id,
                    ]),
                    $buttons = null,
                    $schedule = null
                );
            }

            $this->rejectReason = "";
            $this->rejectCode = "";
            $this->isSuperUser = false;
        }
    }
    public function revokeSV($id)
    {
        $reading = Reading::where('id', $id)->update(
            [
                'accepted1' => 0,
                'reason1' => "",
                'reject_code1' => 0,
            ]
        );
        return $this->render();
    }
    public function revokeSU($id)
    {
        $reading = Reading::where('id', $id)->update(
            [
                'accepted2' => 0,
                'reason2' => "",
                'reject_code2' => 0,
            ]
        );
        return $this->render();
    }

    public function branchCode($code, $name)
    {
        $date_check = date('Y-m');
        if (date('d') < 29) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        $this->branchCode = $code;
        $this->branchName = $name;
        $this->readings = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();
    }

    public function mount()
    {
        $this->branches = Branches::all();
        switch (auth()->user()->type) {
            case 3:
            case 5:
                $this->branches = Branches::where('id', auth()->user()->branch_id)->get();
                break;
            default:
                break;
        }

        $date_check = date('Y-m');
        if (date('d') < 29) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }
        if ($this->branches) {
            $this->branchCode = $this->branches[0]->code;
            $this->branchName = $this->branches[0]->name;
        }

        //$this->branchCode = 50;
        $term = '%' . $date_check . '%';
        $this->readings = Reading::where('created_at', 'like', '%' . $date_check . '%')->get();
    }

    function showImageModal($id)
    {
        $reading = Reading::find($id);
        if (!$reading) {
            $this->alert('error', 'Image Invalid!', [
                'position' => 'top',
            ]);
            return;
        }
        $this->emit('showImageEvent');
        $this->imageData = $reading->image;
    }
}
