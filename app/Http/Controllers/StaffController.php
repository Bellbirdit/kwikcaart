<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function storeStaff(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'contact' => 'required',
                'password' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $username = Str::slug('SM') . (Staff::max('id') + random_int(99, 999999));
            $user = new User();
            $user->name = $request->name;
            $user->role = $request->role;
            $user->type = $request->type;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->contact = $request->contact;
            $user->address = $request->address;
            $user->store_id = auth()->user()->code;
            $user->staff_id = $username;
            $user->status = "active";
            if (isset($request->avatar) && $request->avatar != '') {
                $avatar = $request->file('avatar');
                $path = 'uploads/files/';
                $filename = rand(999, 10000000) . time() . '.' . $avatar->getClientOriginalName();
                $avatar->move($path, $filename);
                $user->avatar = $filename;
            }
            $user->save();

            $usr = User::where('id', $user->id)->first();
            $usr->assignRole($request->role);

            return response()->json([
                'status' => 'success',
                'msg' => 'Staff Created Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function roleTypes(Request $request)
    {
        $roles = Role::where('type', $request->id)->get();

        if (isset($roles) && sizeof($roles) > 0) {
            $html = '<option selected value="">Select role</option>';
            foreach ($roles as $role) {

                $html .= '<option value="' . $role->name . '">' . $role->name . '</option>';
            }

            return response()->json(['status' => 'success', 'html' => $html]);
        } else {

            $html = '<option selected value="">No Role Found</option>';

            return response()->json(['status' => 'fail', 'html' => $html]);
        }
    }

    public function staffCount(Request $request)
    {
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query()->where('type', 2)->where('store_id', auth()->user()->code);

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('name', 'like', '%' . $filterEmail . '%');
        }
        if ($filterStatus != 'All') {
            $result = $result->where('status', $filterStatus);
        }
        $count = $result->count();
        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    public function stafflist(Request $request)
    {
        if(Auth::user()->hasRole('Store')){
            $store_id = Auth::user()->code;
        } else {
            $store_id = Auth::user()->store_id;
        }
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query();

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }
        if ($filterStatus != 'All') {
            $result = $result->where('status', $filterStatus);
        }
        $staff = $result->take($filterLength)->skip($request->offset)->where('store_id', $store_id)->get();
        if (isset($staff) && sizeof($staff) > 0) {
            $html = "";
            foreach ($staff as $staf) {

                if ($staf->avatar == "") {
                    $img = '<img class="img-md img-avatar" src="' . asset('assets/store.png') . '" alt="Store pic" />';
                } else {

                    $img = '<img class="img-md img-avatar"  src="' . asset('uploads/files/' . $staf->avatar) . '" alt="staff pic" />';
                }
                $html .= '
                <div class="col">
                    <div class="card card-user">
                        <div class="card-header">
                        ' . $img . '
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mt-50">' . ucwords($staf->name) . '</h5>
                            <h5 class="card-title mt-50">' . ucwords($staf->role) . '</h5>
                            <div class="card-text text-muted">
                                <p class="m-0">Staff one ID: ' . strtoupper($staf->staff_id) . '</p>
                                <p>' . $staf->email . '</p>
                                <a href="#" class="btn btn-sm btn-brand rounded font-sm mt-15">View details</a>
                                <a href="javascript:;" class="btn btn-sm btn-brand rounded font-sm mt-15 login" id=' . $staf->id . '>login</a>
                            </div>
                        </div>
                    </div>
                </div>

                ';
            }

            return response(['status' => 'success', 'rows' => $html]);
        } else {

            return response(['status' => 'fail']);
        }
    }

    public function stafflogin($id)
    {
        Auth::logout();
        $user = User::find($id);
        Auth::login($user);
        return response(['status' => 'success', 'Login successfully as Staff']);

    }

    public function adminStaff(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'contact' => 'required',
                'password' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $username = Str::slug('SM') . (Staff::max('id') + random_int(99, 999999));
            $user = new User();
            $user->name = $request->name;
            $user->role = $request->role;
            $user->type = $request->type;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->contact = $request->contact;
            $user->address = $request->address;
            $user->store_id = auth()->user()->code;
            $user->staff_id = $username;
            $user->status = "active";
            if (isset($request->avatar) && $request->avatar != '') {
                $avatar = $request->file('avatar');
                $path = 'uploads/files/';
                $filename = rand(999, 10000000) . time() . '.' . $avatar->getClientOriginalName();
                $avatar->move($path, $filename);
                $user->avatar = $filename;
            }
            $user->save();

            $usr = User::where('id', $user->id)->first();
            $usr->assignRole($request->role);

            return response()->json([
                'status' => 'success',
                'msg' => 'Staff Created Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function adminstaffCount(Request $request)
    {
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query()->where('type', 1)->where('store_id', auth()->user()->code);

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('name', 'like', '%' . $filterEmail . '%');
        }
        if ($filterStatus != 'All') {
            $result = $result->where('status', $filterStatus);
        }
        $count = $result->count();
        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    public function adminstafflist(Request $request)
    {
       if(Auth::user()->hasRole('Admin')){
            $store_id = Auth::user()->code;
        } else if(Auth::user()->type=='1'){
            $store_id = Auth::user()->store_id;
        }
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query();

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }
        if ($filterStatus != 'All') {
            $result = $result->where('status', $filterStatus);
        }
        $staff = $result->take($filterLength)->skip($request->offset)->where('store_id', $store_id)->where('type', 1)->where('staff_id', '!=', '')->get();
        if (isset($staff) && sizeof($staff) > 0) {
            $html = "";
            foreach ($staff as $staf) {

                if ($staf->avatar == "") {
                    $img = '<img class="img-md img-avatar" src="' . asset('assets/store.png') . '" alt="Store pic" />';
                } else {

                    $img = '<img class="img-md img-avatar"  src="' . asset('uploads/files/' . $staf->avatar) . '" alt="staff pic" />';
                }
                $html .= '
                <div class="col">
                    <div class="card card-user">
                        <div class="card-header">
                        ' . $img . '
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mt-50">' . ucwords($staf->name) . '</h5>
                            <h5 class="card-title mt-50">' . ucwords($staf->role) . '</h5>
                            <div class="card-text text-muted">
                                <p class="m-0">Staff one ID: ' . strtoupper($staf->staff_id) . '</p>
                                <p>' . $staf->email . '</p>
                                
                                <a href="javascript:;" class="btn btn-sm btn-brand rounded font-sm mt-15 login" id=' . $staf->id . '>login as staff</a>
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            return response(['status' => 'success', 'rows' => $html]);
        } else {

            return response(['status' => 'fail']);
        }
    }

    public function adminstafflogin($id)
    {
        Auth::logout();
        $user = User::find($id);
        Auth::login($user);
        return response(['status' => 'success', 'Login successfully as Staff']);

    }
}
