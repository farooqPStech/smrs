<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\User;
use App\Models\Log;
use App\Models\Branch;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
//use Maatwebsite\Excel\Excel;
use Mail;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
 // require 'PHPMailerAutoload.php';
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
        $users = User::where('active_status', '<' , 2 )->get();


        // if(request()->ajax()) {
        //     $query = User::select('*')->where('active_status',1);
        //     return datatables()->eloquent($query)
        //     ->addIndexColumn()
        //     ->addColumn('action', function ($query) {
        //         return '
        //         <a href="/user/edit/'.$query->id.'" class="btn btn-xs btn-primary"><i class="fa fas fa-edit"></i> Edit</a><br><br><a href="/user/resetpassword/'.$query->id.'" class="btn btn-xs" style="background:orange; color:white"><i class="fa fas fa-edit" style="color:white"></i> Reset Password</a><br><br>
        //         <button class="btn btn-xs btn-danger" onclick="deleteConfirmation('. $query->id .')"><i class="fa fas fa-trash"></i> Delete</button>';
        //     })
        //     ->editColumn('created_at', function ($request) {
        //     return $request->created_at->format('d-M-Y'); // human readable format
        //     })
        //     ->editColumn('active_status', function ($request) {
        //         if($request->active_status == 1) return 'Active'; // human readable format
        //         if($request->active_status == 0) return 'Inactive';
        //         return 'Error';
        //     })
        //     ->editColumn('type', function ($request) {
        //         if($request->type == 1) return 'Admin';
        //         if($request->type == 2) return 'Supervuser / Final Approval';
        //         if($request->type == 3) return 'Supervisor';
        //         if($request->type == 4) return 'Meter Reader';
        //         if($request->type == 5) return 'Temporary Supervuser / Final Approval';
        //         if($request->type == 6) return 'Temporary Supervisor';
        //         return 'Error';
        //     })
        //     // ->addColumn('status', function ($query) {
        //     //     return $query->status_str();
        //     //   })
        //     // ->addColumn('address', function ($query) {
        //         // return $query->unit->address1 . ' ' . $query->unit->address2;
        //     // })
        //     ->rawColumns(['action'])
        //     ->toJson();
        // }


        return view('user.list', compact('users'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $branches = Branch::all();

        $items = array(
            'user' => $user,
            'branches' => $branches,
        );

        return view('user.edit')->with($items);
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        if ($user) {
            Password::sendResetLink(['email' => $user->email]);
            \Session::flash('status', 'Reset password link sent to your email.');
            return Redirect::back();
        }
        else
        {
             \Session::flash('status', 'Email not registered!');
            return Redirect::back();
        }
    }

    public function update(Request $request)
    {

        $update = User::find($request->id);

        if ($request->password != '' && $request->password != null) {
            if ($request->password == $request->confirmPassword) {

                $validator = Validator::make($request->all(), [
                'password' => 'required|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^',
                'username' => 'required',
                'email' => 'required',
                'branch' => 'required',
                'type' => 'required',
                ]);

                if ($validator->fails()) {
                   \Session::flash('status', $validator->messages()->first());
                    return Redirect::back();
                }

                $update->name = $request->name;
                $update->username = $request->username;
                $update->full_name = $request->fullname;
                $update->mobile_phone = $request->mobilephone;
                $update->email = $request->email;
                $update->active_status = $request->status;
                $update->branch_id = $request->branch;
                $update->password = bcrypt($request->password);
                $update->type =$request->type;
                $update->save();

                $new = new Log();
                $new->user = auth()->user()->id;
                $new->activity = "User Updated At: " . $update->id;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();

                \Session::flash('status', 'User Updated!');
                return Redirect::back();
            }
            else
            {
                \Session::flash('status', 'Password not match!');
                return Redirect::back();
            }
        }
        elseif ($request->password == '' && $request->password == null) {


                $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required',
                'branch' => 'required',
                'type' => 'required',
                ]);

                if ($validator->fails()) {
                   \Session::flash('status', $validator->messages()->first());
                    return Redirect::back();
                }

                $update->name = $request->name;
                $update->username = $request->username;
                $update->full_name = $request->fullname;
                $update->mobile_phone = $request->mobilephone;
                $update->email = $request->email;
                $update->active_status = $request->status;
                $update->branch_id = $request->branch;
                $update->type =$request->type;
                $update->save();

                $new = new Log();
                $new->user = auth()->user()->id;
                $new->activity = "User Updated At: " . $update->id;
                $new->month = Date('m');
                $new->year = Date('Y');
                $new->save();

                \Session::flash('status', 'User Updated!');
                return Redirect::back();
        }
        else{
            \Session::flash('status', 'Password not match!');
                return Redirect::back();
        }
    }

    public function addUser()
    {

        $branches = Branch::all();

        $items = array(
            'branches' => $branches,
        );

        return view('user.addUser')->with($items);
    }

    public function addNewUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
                // 'password' => 'required|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^',
                'username' => 'required',
                'email' => 'required',
                'branch' => 'required',
                'type' => 'required',
        ]);

        if ($validator->fails()) {
           \Session::flash('status', $validator->messages()->first());
            return Redirect::back();
        }

        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
        // if ($request->password == $request->confirmPassword) {
        $update = new User();
        $update->name = $request->name;
        $update->username = $request->username;
        $update->email = $request->email;
        $update->full_name = $request->fullname;
        $update->mobile_phone = $request->mobilephone;
        $update->active_status = $request->status;
        $update->password = bcrypt($password);
        $update->branch_id = $request->branch;
        $update->type = $request->type;
        $update->first_time_login = 1;
        $update->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "User Added At: " . $update->id;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        $data = [
          'subject' => 'SMRS Initial Password',
          'email' => $request->email,
          'password' => 'Your Password is: '.$password,
          'fullname' => $request->fullname,
          'username' => $request->username,
        ];

        Mail::send('user.firstTimeEmail', $data, function($message) use ($data) {
          $message->to($data['email'])
          ->subject($data['subject']);
        });

        // return back()->with(['message' => 'Email successfully sent!']);

        \Session::flash('status', 'User Added!');
        return Redirect::back();

        // }
        // else
        // {
            // \Session::flash('status', 'Password not match!');
            // return Redirect::back();
        // }
    }

    public function changePassword()
    {
        return view('user.changePassword');
    }

    public function updateChangePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
                'password' => 'required|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^',
        ]);

        if ($validator->fails() || $request->password != $request->passwordConfirm) {
           \Session::flash('status', $validator->messages()->first());
            return Redirect::back();
        }

        // dd($request);

        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->first_time_login = 0;
        $user->save();

        \Session::flash('status', 'Password Updated!');

        auth()->logout();
        return view('auth.login');
    }

    public function removeUser($id)
    {

        $user = User::find($id);
        $user->active_status = 0;
        $user->save();

        $new = new Log();
        $new->user = auth()->user()->id;
        $new->activity = "User Deactivated At: " . $user;
        $new->month = Date('m');
        $new->year = Date('Y');
        $new->save();

        \Session::flash('status', 'User Removed!');
        return Redirect::back();
    }

    public function export()
    {
        //return Excel::download(new UsersExport, 'user.xlsx');
        return Excel::download(new UsersExport, 'user.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // public function email()
    // {
    //      $data = [
    //       'subject' => 'SMRS Initial Password',
    //       'email' => 'ismaell@gmail.com',
    //       'password' => 'Your Password is: password',
    //       'fullname' => 'Ismael bin azman',
    //       'username' => 'Ismael',
    //     ];
    //     return view('user.firstTimeEmail')->with($data);
    // }
}
