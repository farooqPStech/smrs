<?php

namespace App\Http\Controllers\Api;

use App\Models\Consumer;
// use Illuminate\Database\Eloquent\Collection;
use App\Models\IssueCode;
use App\Models\Meter;
use App\Models\Reading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;

class JDEDataController
{
    //formula
    //actual consumption = ((current_reading - prev) * corrected_vol ) / z factor 
    // Contractual CF = corrected_vol / z_factor
    public function loadHistoricalData(Request $request)
    {

        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()->first(),
            ]);
        }

        $data = $request->file('fileToUpload');
        $reads = file($data);
        $index = 0;
        $saved = 0;
        $updated = 0;
        foreach ($reads as $read) {
            $index++;
            //skip  first line
            if ($index > 1) {
                $parse = str_getcsv($read, ',');
                if ($request->type == 'reading') {
                    $consumer = Consumer::where('consumer_number', $parse[0])->first();
                    if ($consumer) {
                        $meter = Meter::where('meter_number', $parse[5])->first();
                        if ($meter) {
                            $readingDate = Carbon::createFromFormat('d/m/Y', $parse[12]);
                            $issueCode = IssueCode::where('code_number', '0' . $parse[7])->first();
                            $reading = Reading::where('reading_date', $readingDate->format('Y-m-d'))
                                ->where('meter_id', $meter->id)
                                ->first();
                            //dd($readingDate->format('Y-m-d'));
                            if (!$reading) {
                                $reading = new Reading();
                                $saved++;
                            } else {
                                $updated++;
                            }

                            $reading->meter_id = $meter->id;
                            $reading->billable = 0;
                            $reading->reading = $parse[11];
                            $reading->current_consumption = $parse[13];

                            if (strlen($parse[15]) > 0) {
                                $reading->mmbtu = str_replace(',', '', $parse[15]);
                            } else {
                                $reading->mmbtu = 0;
                            }

                            $reading->accepted1 = 2; //set to approved
                            $reading->accepted2 = 2; //set to approved
                            $issueCode ? $reading->issue_code_id = $issueCode->id : $reading->issue_code_id = 4; // 4 - id normal reading
                            $reading->obstacle_code_id = 16;
                            $reading->adjustment_consumption = $parse[17];
                            $reading->reading_date = $readingDate;
                            $reading->actual_consumption = $parse[13];
                            $reading->corrected_volume = $parse[22];
                            $reading->new_cv = $parse[25];
                            $reading->created_at = $readingDate;
                            $reading->updated_at = $readingDate;
                            $reading->save();

                            if($meter){
                                $meter->z_factor = $parse[24];
                                $meter->save();
                            }
                        }
                    }
                } elseif ($request->type == 'consumer') {
                    $consumer = Consumer::where('consumer_number', $parse[4])->first();

                    if (!$consumer) {
                        $consumer = new Consumer();
                        $consumer->prefix = 1;
                        $consumer->route_id = 0;
                        $consumer->consumer_number = $parse[0];
                        $consumer->old_account_number = $parse[4];
                        $consumer->tariff_code = 0;
                        $consumer->consumer_name = $parse[1];
                        $consumer->consumer_address_1 = $parse[5];
                        $consumer->consumer_type = 11; //hardcode to industrial
                        $consumer->pic_name = $parse[13];
                        $consumer->pic_code = $parse[12];
                        $saved++;
                    } else {
                        $consumer->prefix = 1;
                        $consumer->route_id = 0;
                        $consumer->old_account_number = $parse[4];
                        $consumer->consumer_name = $parse[1];
                        $consumer->pic_name = $parse[13];
                        $consumer->pic_code = $parse[12];
                        $updated++;
                    }
                    $consumer->save();
                } elseif ($request->type == 'meter') {
                    $consumer = Consumer::where('consumer_number', $parse[1])->first();
                    $meter = Meter::where('meter_number', $parse[5])->first();
                    if (!$meter) {
                        $meter = new Meter();
                        if ($consumer) {
                            $meter->consumer_id = $consumer->id;
                        } else {
                            $meter->consumer_id = 0;
                        }
                        $saved++;
                    } else {
                        $updated++;
                    }

                    $meter->prefix = 2;
                    $meter->meter_number = $parse[5];
                    $meter->meter_sequence_number = $parse[8];
                    $meter->unit_measurement = strlen($parse[6]) > 0 ? $parse[6] : 0;
                    $meter->dial_length = $parse[7];
                    $meter->meter_location_id = 0;
                    $meter->meter_type = $parse[9];
                    $meter->last_reading = $parse[10];
                    if (strlen($parse[11] > 0)) {
                        $readingDate = Carbon::createFromFormat('d/m/Y', $parse[11]);
                        $meter->last_reading_date = $readingDate;
                    }
                    $meter->last_reading_status = $parse[12];
                    $meter->daily_average_consumption = $parse[13];
                    if (strlen($parse[14] > 0)) {
                        $installationDate = Carbon::createFromFormat('d/m/Y', $parse[14]);
                        $meter->meter_installation_date = $installationDate;
                    }

                    $meter->replacement_consumption = $parse[15];
                    $meter->control_reading = $parse[16];
                    // $meter->meter_status = $parse[9];
                    // $meter->disconnection_reading = $parse[9];
                    // $meter->disconnection_date = $parse[9];
                    $meter->meter_reading_sequence = $parse[18];
                    $meter->temperature = $parse[19];
                    $meter->pressure = str_replace(',', '', $parse[20]);
                    $meter->cf_factor = $parse[22];
                    //$meter->z_factor = $parse[9];
                    $meter->corrected_volume = $parse[21];
                    $meter->save();
                    if ($consumer) {
                        
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
            'updated' => $updated,
            'total' => $index - 1,
        ]);

    }

}
