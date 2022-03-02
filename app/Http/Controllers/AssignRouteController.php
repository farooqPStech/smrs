<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\RouteController;
use App\Models\Route;
use App\Models\Branch;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Console\Output\ConsoleOutput;

class AssignRouteController extends Controller
{
    //
    public function index(){

        $routes = Route::all();
        //$branches = Branch::all();
        $branches = Branch::join('users', 'branches.id', '=', 'users.branch_id')->select(
            'users.id as userid', 'users.full_name as fullname', 'users.branch_id as user_branch_id',
            'branches.id as branchid', 'branches.code', 'branches.name', 'users.email as username'
        )->get();
        $users = User::all();


        return view('route_assign.index', [

            'routes' => $routes,
            'branches' => $branches,
            'users'  => $users

        ]);
    }

    public function edit($id){


        $routes = Route::where('user_id', NULL)->select('*')->get();
        $user = User::find($id);
        $branchid = Branch::where('id', $user->branch_id)->value('code');


        return view('route_assign.test', [

            'routes' => $routes,
            'user' => $id,
            'branch' =>$branchid

        ]);

    }

    public function update(Request $request)
    {
        $request->all();
        $newAssignRouteSV = Route::Where('id', $request->route)->update(['user_id' => $request->userid]);

        if($newAssignRouteSV) {
                $new = new Log();
                $new->user = auth()->user()->id;
                $new->activity = "Route Assigned at: " . $request->userid;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();
        }

        \Session::flash('status', 'Route Assigned Successfully!');
        return Redirect::back();

        /*foreach($request->routes as $route){
            Route::where('route', $route)->update(['user_id' => $request->user_id]);
        }

        $routes = Route::all();
        //$branches = Branch::all();
        $branches = Branch::join('users', 'branches.id', '=', 'users.branch_id')->select(
            'users.id as userid', 'users.full_name as fullname', 'users.branch_id as user_branch_id',
            'branches.id as branchid', 'branches.code', 'branches.name', 'users.email as username'
        )->get();
        $users = User::all();

        return view('route_assign.index', [

            'routes' => $routes,
            'branches' => $branches,
            'users'  => $users

        ]);*/
    }

    function delete($id){
        $out = new ConsoleOutput();
        try
        {
            $route = Route::where('id', $id)
                ->limit(1)
                ->update(['user_id' => NULL]);

            if($route){
                \Session::flash('status', 'Route Assigned Removed Successfully!');
                return Redirect::back();
            }
            else{
                $out->writeln($route);
            }

        }
        catch (\Exception $e)
        {
            $error['error_log'] = $e;
            $out->writeln($e);
            return $this->returnResponse (2, $error, "Error Encountered. See Log");
        }





        /*$routes = Route::all();
        //$branches = Branch::all();
        $branches = Branch::join('users', 'branches.id', '=', 'users.branch_id')->select(
            'users.id as userid', 'users.full_name as fullname', 'users.branch_id as user_branch_id',
            'branches.id as branchid', 'branches.code', 'branches.name', 'users.email as username'
        )->get();
        $users = User::all();



        return view('route_assign.index', [

            'routes' => $routes,
            'branches' => $branches,
            'users'  => $users

        ]);*/
    }
}


