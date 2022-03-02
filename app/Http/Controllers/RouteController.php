<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Consumer;
use App\Models\Handheld;
use App\Models\HandheldRoute;
use App\Models\Log;
use App\Models\Route;
use App\Models\RouteAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

// use OneSignal;

class RouteController extends Controller
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
    public function list() {
        // $branch = Branch::where('id',auth()->user()->branch_id)->first();

        // if(request()->ajax()) {
        //     // $query = Route::select('*');
        // $query = Route::leftjoin('handheld_routes', 'routes.id', '=', 'handheld_routes.handheld_id')->leftjoin('handhelds','handheld_routes.handheld_id', '=', 'handhelds.id')->distinct()->where('routes.branch_code',$branch->code)->select('routes.*','handhelds.uuid');
        //     return datatables()->eloquent($query)
        //     ->addIndexColumn()

        //     ->addColumn('handheld', function ($query) {
        //         return $query->uuid;
        //       })

        //     ->toJson();
        // }

        // return view('route.list');
        $handheldRoutes = HandheldRoute::with(['route' => function ($query) {
            $query->orderBy('route');
        }])
            ->with('route.consumers')
            ->get();

        return view('route.list', compact('handheldRoutes'));
    }

    public function assignRoute()
    {
        $handheldRoutes = HandheldRoute::where('created_at', 'like', '%' . date('Y-m') . '%')->get();
        $assignedRoutesIds = $handheldRoutes->pluck('route_id');

        $code = Branch::where('id', auth()->user()->branch_id)->first();
        if (auth()->user()->type == 1 || auth()->user()->type == 2 || auth()->user()->type == 5) {
            $routes = Route::whereNotIn('id', $assignedRoutesIds)
            //->where('status', 1)
                ->get();
        } else {
            $routes = Route::whereNotIn('id', $assignedRoutesIds)
            //->where('branch_code', $code->code)->where('status', 1)
                ->get();
        }

        $handhelds = Handheld::where('branch_id', auth()->user()->branch_id)->get();
        $items = array(
            'handheldRoutes' => $handheldRoutes,
            'handhelds' => $handhelds,
            'routes' => $routes,
        );

        return view('route.assignRoute')->with($items);
    }

    public function assignRoutePrev()
    {

        $routeAssignments = RouteAssignment::all();

        for ($i = 0; $i < count($routeAssignments); $i++) {

            $new = new HandheldRoute();
            $new->route_id = $routeAssignments[$i]->route_id;
            $new->handheld_id = $routeAssignments[$i]->handheld_id;
            $new->status = 1;

            $new->save();
        }

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Previous route assign settings applied";
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'All Previous Settings applied!');
        return Redirect::back();

    }

    public function routeAssignment()
    {

        $routeAssignments = RouteAssignment::all();

        $code = Branch::where('id', auth()->user()->branch_id)->first();

        $routes = Route::whereNotIn('id', $routeAssignments->pluck('route_id'))->where('branch_code', $code->code)->get();

        $handhelds = Handheld::where('branch_id', auth()->user()->branch_id)->get();

        for ($i = 0; $i < count($handhelds); $i++) {

            $new = Branch::find($handhelds[$i]->branch_id);
            $handhelds[$i]->branch_code = $new->code;

        }

        for ($i = 0; $i < count($routeAssignments); $i++) {
            $new = Route::where('id', $routeAssignments[$i]->route_id)->first();
            $routeAssignments[$i]->route = $new->route;
            $routeAssignments[$i]->branch_code = $new->branch_code;

            $branchcode = Branch::where('code', $new->branch_code)->first();
            $routeAssignments[$i]->branch = $branchcode;

            $new = Handheld::where('id', $routeAssignments[$i]->handheld_id)->first();
            $routeAssignments[$i]->handheld = $new;

            $user = User::Where('uuid', $new->uuid)->first();
            $routeAssignments[$i]->user = $user;

        }

        $items = array(
            'routeAssignments' => $routeAssignments,
            'handhelds' => $handhelds,
            'routes' => $routes,
        );
        return view('route.routeAssignment')->with($items);
    }

    public function routeAssignmentUnassign($ids)
    {

        $collection = explode(',', $ids);

        if ($collection) {
            for ($i = 0; $i < count($collection); $i++) {

                $routeAssignment = RouteAssignment::find($collection[$i])->delete();

            }

            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Route Unassign table setting at id: " . implode(',', $collection);
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Route Unassigned at setting table!');
            return Redirect::back();
        } else {
            \Session::flash('status', 'Error!');
            return Redirect::back();
        }
    }

    public function routeAssignmentNewAssign($routes, $ids)
    {
        $routes = explode(',', $routes);

        $ids = explode(',', $ids);

        for ($i = 0; $i < count($routes); $i++) {
            for ($j = $i + 1; $j < count($routes); $j++) {
                if ($routes[$i]) {
                    if ($routes[$j] == $routes[$i]) {
                        \Session::flash('status', 'Route Clash with Other Handheld!');
                        return Redirect::back();
                    }
                }
            }
        }

        for ($i = 0; $i < count($routes); $i++) {

            if ($routes[$i]) {
                $new = new RouteAssignment();
                $new->route_id = $routes[$i];
                $new->handheld_id = $ids[$i];
                $new->status = 1;
                $new->save();
            }
        }

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Route Assigned at table setting at id: " . implode(',', $ids);
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Route Assigned at setting table!');
        return Redirect::back();
    }

    public function unassignSingleRoute($id)
    {
        $handheldRoutes = HandheldRoute::findOrFail($id)->delete();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Unassign at id: " ;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Route Assigned Removed Successfully!');
        return Redirect::back();

    }

    public function unassign($ids)
    {

        $collection = explode(',', $ids);

        if ($collection) {
            for ($i = 0; $i < count($collection); $i++) {

                $handheldRoutes = HandheldRoute::find($collection[$i])->delete();

            }

            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Unassign at id: " . implode(',', $collection);
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Route Unassigned!');
            return Redirect::back();
        } else {
            \Session::flash('status', 'Error!');
            return Redirect::back();
        }

    }

    public function newAssignRoute($routes, $ids)
    {

        $routes = explode(',', $routes);

        $ids = explode(',', $ids);

        for ($i = 0; $i < count($routes); $i++) {
            for ($j = $i + 1; $j < count($routes); $j++) {
                if ($routes[$i]) {
                    if ($routes[$j] == $routes[$i]) {
                        \Session::flash('status', 'Route Clash with Other Handheld!');
                        return Redirect::back();
                    }
                }
            }
        }

        for ($i = 0; $i < count($routes); $i++) {

            if ($routes[$i]) {
                $new = new HandheldRoute();
                $new->route_id = $routes[$i];
                $new->handheld_id = $ids[$i];
                $new->status = 1;
                $new->date = date('Y-m-d');
                $new->save();

                //for pop up on handheld

                $select = Handheld::Where('id', $ids[$i])->first();
                if ($select->player_id) {
                    // \OneSignal::sendNotificationToAll('New Route Assigned');
                    // code...
                    \OneSignal::sendNotificationToUser(
                        "New Route Assigned!",
                        $select->player_id,
                        $url = null,
                        $data = response()->json([
                            'success' => true,
                            'data' => 'A new Route Assigned!']),
                        $buttons = null,
                        $schedule = null
                    );
                }
            }

        }

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Route Assigned at: " . implode(',', $ids);
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Route Assigned Successfully!');
        return Redirect::back();
    }

    public function assignSingleRoute(Request $request)
    {
        // dd($request->all());
        $new = new HandheldRoute();
        $new->route_id = $request->route;
        $new->handheld_id = $request->id;
        $new->status = 1;
        $new->date = date('Y-m-d');
        $new->save();

        //for pop up on handheld
        $select = Handheld::find($request->id);
        if ($select->player_id) {
            \OneSignal::sendNotificationToUser(
                "New Route Assigned!",
                $select->player_id,
                $url = null,
                $data = response()->json([
                    'success' => true,
                    'data' => 'A new Route Assigned!']),
                $buttons = null,
                $schedule = null
            );
        }

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Route Assigned to : " . $select->label;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Route Assigned Successfully!');
        return Redirect::back();
    }

    public function adjustRoute()
    {
        $handheldRoutes = HandheldRoute::where('created_at', 'like', '%' . date('Y-m') . '%')->get();

        for ($i = 0; $i < count($handheldRoutes); $i++) {
            $new = Route::where('id', $handheldRoutes[$i]->route_id)->first();
            $handheldRoutes[$i]->route = $new->route;
            $handheldRoutes[$i]->branch_code = $new->branch_code;

            $new = Handheld::where('id', $handheldRoutes[$i]->handheld_id)->first();
            $handheldRoutes[$i]->handheld = $new->uuid;
        }

        $notRoutes = Route::whereNotIn('id', $handheldRoutes->pluck('route_id'))->get();

        $routes = Route::whereIn('id', $handheldRoutes->pluck('route_id'))->get();

        $handhelds = Handheld::all();

        for ($i = 0; $i < count($handhelds); $i++) {

            $new = Branch::find($handhelds[$i]->branch_id);
            $handhelds[$i]->branch_code = $new->code;

        }

        $items = array(
            'handheldRoutes' => $handheldRoutes,
            'handhelds' => $handhelds,
            'routes' => $routes,
            // 'allRoute' => $allRoute,
            'notRoutes' => $notRoutes,
        );

        return view('route.adjustRoute')->with($items);
    }

    public function combineRouteTable()
    {

        $handheldRoutes = HandheldRoute::where('created_at', 'like', '%' . date('Y-m') . '%')->get();
        $tempRouteIds = array();

        for ($i = 0; $i < count($handheldRoutes); $i++) {
            $new = Route::where('id', $handheldRoutes[$i]->route_id)->first();
            $handheldRoutes[$i]->route = $new->route;
            $handheldRoutes[$i]->branch_code = $new->branch_code;
            array_push($tempRouteIds, $handheldRoutes[$i]->route_id);
            if ($new->combine != null || $new->combine != '') {
                $tempArray = explode(',', $new->combine);
                foreach ($tempArray as $ta) {
                    array_push($tempRouteIds, intval($ta));
                }
            }

            $new = Handheld::where('id', $handheldRoutes[$i]->handheld_id)->first();
            $handheldRoutes[$i]->handheld = $new->uuid;
        }

        $combinedRoutes = Route::whereNotNull('combine')->where('created_at', 'like', '%' . date('Y-m') . '%')->get();
        foreach ($combinedRoutes as $combinedRoute) {

            $tempArray = explode(',', $combinedRoute->combine);
            foreach ($tempArray as $ta) {
                array_push($tempRouteIds, intval($ta));
            }
        }

        // dd($tempRouteIds);

        if (request()->ajax()) {
            $ids = Route::whereNotIn('id', $tempRouteIds)->where('status', 1);
            return datatables()->eloquent($ids)
                ->addIndexColumn()
                ->toJson();
        }
    }

    public function assignCombineRoute(Request $request)
    {

        $a = $request->routes;
        $routes = explode(',', $a);

        if (count($routes) <= 1) {
            \Session::flash('status', 'Please select Minimum 2 Route!');
            return Redirect::back();
        }

        $array = array();
        for ($i = 0; $i < count($routes); $i++) {

            $data = Route::where('id', $routes[$i])->first();
            array_push($array, $data->route);

        }

        for ($i = 0; $i < count($routes); $i++) {
            $check = Route::whereNotNull('combine')->get();

            for ($j = 0; $j < count($check); $j++) {

                $id = explode(',', $check[$j]->combine);

                for ($k = 0; $k < count($id); $k++) {

                    if ($id[$k] == $routes[$i]) {

                        \Session::flash('status', 'Route already Combined');
                        return Redirect::back();

                    }
                }
            }
        }

        $branch = Branch::where('id', auth()->user()->branch_id)->first();

        $new = new Route();
        $new->route = implode(",", $array);
        $new->combine = $request->routes;
        $new->comment = 'Combine Route';
        $new->status = 'Combine Route';
        $new->branch_code = $branch->code;
        $new->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Route Combined at : " . implode(",", $array);
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'Route Combined');
        return Redirect::back();

        // $search = CombinedRoute::max('combine_id');

        // $b = 0;
        // for ($i=0; $i < count($routes); $i++) {
        //     $check = CombinedRoute::where('route_id',$routes[$i])->first();

        //     if($check != null || $check != ''){
        //         break;
        //     }
        // }

        // if ($check == null || $check == '') {

        //     if ($search == '' || $search == null) {
        //         $search = 1;
        //     }
        //     else
        //     {
        //         $search += 1;
        //     }

        //     for ($i=0; $i < count($routes); $i++) {

        //         $new = new CombinedRoute();
        //         $new->combine_id = $search;
        //         $new->route_id = $routes[$i];
        //         $new->save();
        //     }

        //     $new = new Log();
        //     $new->user = auth()->user()->id;
        //     $new->activity = "Route Combined At Id: " . implode(",",$routes);
        //     $new->month = Date('m');
        //     $new->year = Date('Y');
        //     $new->save();

        //     \Session::flash('status', 'Route Combined!');
        //     return Redirect::back();
        // }
        // else
        // {
        //      \Session::flash('status', 'Route Already Assigned');
        //     return Redirect::back();
        // }
    }

    public function routeIDTable()
    {
        if (request()->ajax()) {
            $query = Route::select('*');
            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->toJson();
        }
    }

    public function splitRouteTable()
    {
        if (request()->ajax()) {
            $query = Route::select('*');
            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->toJson();
        }
    }

    public function changeHandheldTable()
    {

        if (request()->ajax()) {
            $query = HandheldRoute::where('created_at', 'like', '%' . date('Y-m') . '%');
            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->toJson();
        }

        // if(request()->ajax()) {
        //     $query = Route::select('*');
        //     return datatables()->eloquent($query)
        //     ->addIndexColumn()
        //     ->toJson();
        // }
    }

    public function changeHandheld(Request $request)
    {

        $select = HandheldRoute::where('route_id', $request->route)->where('created_at', 'like', '%' . date('Y-m') . '%')->first();

        if ($select) {

            $select->handheld_id = $request->handheld;
            $select->save();

            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Handheld Changed At: " . $select->id;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();

            \Session::flash('status', 'Handheld Changed!');
            return Redirect::back();
        } else {
            \Session::flash('status', 'Route and Handheld Not found');
            return Redirect::back();
        }

    }

    public function getRoute($id)
    {
        $query = Consumer::where('route_id', $id);
        return datatables()->eloquent($query)
            ->toJson();
    }

    public function getSubRoute($id)
    {

        $route = Route::find($id);
        $route = Route::where('route', $route->route)->whereNotNull('sub')->where('status', 1);

        return datatables()->eloquent($route)
            ->toJson();
    }

    public function updateSubRoute($id, $startSeq, $endSeq)
    {

        // dd($endSeq);
        $detail = Route::find($id);

        $route = new Route();
        $route->route = $detail->route;
        $route->comment = '';
        $route->status = $detail->status;

        $maxSub = Route::where('route', $detail->route)->max('sub');
        if ($maxSub == null || $maxSub == '') {
            $route->sub = 1;
        } else {
            $route->sub = $maxSub + 1;
        }
        $route->combine = $detail->combine;
        $route->branch_code = $detail->branch_code;
        $route->hht_id = $detail->hht_id;
        $route->address = $detail->address;
        $route->t_ac = $detail->t_ac;

        $bool = $route->save();

        if ($bool) {
            $consumers = Consumer::Where('id', '>=', $startSeq)->Where('id', '<=', $endSeq)->Where('route_id', $id)->update(['route_id' => $route->id]);

            if (!$consumers) {
                $delete = Route::where('id', $route->id)->delete();
            }
        }

        // $comsumers = Consumer::where('id','>=',$startSeq)->where('id','<='$endSeq)->update(['route_id' => $route->id]);

        return json_encode($consumers);
        // echo json_encode($consumers);
    }

    public function updateSubRouteSelected($routes, $id)
    {

        $routelist = explode(',', $routes);

        $detail = Route::find($id);

        $route = new Route();
        $route->route = $detail->route;
        $route->comment = '';
        $route->status = $detail->status;

        $maxSub = Route::where('route', $detail->route)->where('status', 1)->max('sub');
        if ($maxSub == null || $maxSub == '') {
            $route->sub = 1;
        } else {
            $route->sub = $maxSub + 1;
        }
        $route->combine = $detail->combine;
        $route->branch_code = $detail->branch_code;
        $route->hht_id = $detail->hht_id;
        $route->address = $detail->address;
        $route->t_ac = $detail->t_ac;

        $bool = $route->save();

        if ($bool) {
            $consumers = Consumer::WhereIn('id', $routelist)->Where('route_id', $id)->update(['route_id' => $route->id]);

            if (!$consumers) {
                $delete = Route::where('id', $route->id)->delete();
            }
        }

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "Route Split at : " . $route;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();
        // $comsumers = Consumer::where('id','>=',$startSeq)->where('id','<='$endSeq)->update(['route_id' => $route->id]);

        return json_encode($consumers);
        // echo json_encode($consumers);
    }

    public function updateSplitRouteSelected($routes)
    {

        $routelist = explode(',', $routes);

        for ($i = 0; $i < count($routelist); $i++) {
            $data = Route::find($routelist[$i]);

            $data->status = 0;
            $data->save();

            $mainRoute = Route::where('route', $data->route)->where('sub', null)->first();

            $consumers = Consumer::Where('route_id', $routelist[$i])->update(['route_id' => $mainRoute->id]);

            $new = new Log();
            $new->user = auth()->user()->id;
            $new->activity = "Route Unsplit at : " . $data;
            $new->month = Date('m');
            $new->year = Date('Y');
            $new->save();
        }

        return json_encode($consumers);
        // echo json_encode($consumers);
    }

    public function unassignCombineRoute(Request $request)
    {
        $a = $request->routes;
        $routes = explode(',', $a);

        $array = array();
        for ($i = 0; $i < count($routes); $i++) {

            $data = Route::where('id', $routes[$i])->first();
            if ($data->combine) {

                $data->status = 0;
                $data->save();

                $new = new Log();
                $new->user = auth()->user()->id;
                $new->activity = "Route Uncombined at : " . $data;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();

            }

        }

        \Session::flash('status', 'Route Uncombined');
        return Redirect::back();
    }
}
