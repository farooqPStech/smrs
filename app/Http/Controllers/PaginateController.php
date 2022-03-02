<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reading;

class PaginateController extends Controller
{
    //

    public static function index(){
        $date_check = date('Y-m');
        if(date('d') < 29) {
            $date_check = date('Y-m', strtotime('-28 days'));
        } else {
            $date_check = date('Y-m');
        }

        $term = '%' . $date_check . '%';
        $readings =Reading::where('created_at', 'like', '%' . $date_check . '%')->paginate(15);
    }

}
