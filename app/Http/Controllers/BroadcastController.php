<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Broadcast;
use App\Models\Handheld;
use App\Models\Log;
use Illuminate\Support\Facades\Redirect;



class BroadcastController extends Controller
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

        $broadcasts  = Broadcast::all();


        $items = array(
            'broadcasts' => $broadcasts, 
        );
        return view('broadcast.list')->with($items);
    }

    public function addBroadcast()
    {

          $device = Handheld::all();
          $items = array(
            'device' => $device, 
        );

        return view('broadcast.add')->with($items);
    }

    public function addNewBroadcast(Request $request)
    {

        $broadcast = new Broadcast();
        $broadcast->title = $request->title;
        $broadcast->content = $request->content;
        $broadcast->send_to = $request->device; 
        $broadcast->save();

       
        \Session::flash('status', 'New Broadcast Added!');
        return Redirect::back();

    }

    public function updateBroadcast(Request $request)
    {
        $broadcast  = Broadcast::find($request->id);
        $broadcast->title = $request->title;
        $broadcast->content = $request->content;
        $broadcast->send_to = $request->device;
        $broadcast->save();


        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Broadcast Updated at: " . $broadcast;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Broadcast Updated!');
        return Redirect::back();
    }
    
    public function edit($id)
    {

        $broadcast  = Broadcast::find($id);
        $device = Handheld::all();

        $items = array(
            'broadcast' => $broadcast, 
            'device' => $device, 
        );
        return view('broadcast.edit')->with($items);
    }

    public function sendBroadcast($id)
    {

        $broadcast  = Broadcast::find($id);

        if ($broadcast->send_to == 0) {

            \OneSignal::sendNotificationToAll($broadcast->content);
        }
        else{
            $select = Handheld::Where('id',$broadcast->send_to)->first();
            if ($select->player_id) {
                // \OneSignal::sendNotificationToAll('New Route Assigned');
                // code...
                \OneSignal::sendNotificationToUser(
                    $broadcast->content,
                    $select->player_id,
                    $url = null,
                    $data = response()->json([
                            'success' => true,
                            'data' => $broadcast->content]),
                    $buttons = null,
                    $schedule = null
                );
            }

        }
        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Broadcast send at: ". $id;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Broadcast Sent!');
        return Redirect::back();
    }
    
    public function remove($id)
    {

        $broadcast = Broadcast::find($id);
        $broadcast->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Broadcast Deleted At: " . $broadcast;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Broadcast Deleted!');
        return Redirect::back();
    }
}
