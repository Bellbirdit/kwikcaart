<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Order;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
                'email' => 'required|email|unique:users',
                'contact' => 'required',
                'password' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            if (User::where('code', $request->code)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => "Store Code already taken!"]);
            }
            $user = new User();
            $user->name = $request->name;
            $user->code = $request->code;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->contact = $request->contact;
            $user->address = $request->address;
             $user->timing = $request->timing;
            $user->longitude = $request->longitude;
            $user->latitude = $request->latitude;
            $user->location = $request->location;

            $user->type = '2';
            $user->emirate = $request->emirate;
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
            $usr->assignRole('Store');

            // Mail::send('emails.add_user', ['user' => $user,'password'=>$pin ], function ($message) use ($user) {
            //     $message->to($user->email)
            //         ->from('noreply@baramdatsol.com','Allens Empreas')
            //         ->subject("User Account");
            // });
            $responseArray = [];
            $responseArray['token'] = $usr->createToken('safeer')->accessToken;
            return response()->json([
                'status' => 'success',
                'msg' => 'Store Created Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function Count(Request $request)
    {
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query();

        $result = $result->whereHas('roles', function ($q) {

            $q->where('name', 'Store');
        });

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
    function list(Request $request) {

        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = User::query();

        $result = $result->whereHas('roles', function ($q) {

            $q->where('name', 'Store');
        });

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }

        if ($filterStatus != 'All') {

            $result = $result->where('status', $filterStatus);
        }
        $stores = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();

        if (isset($stores) && sizeof($stores) > 0) {
            $html = "";
            foreach ($stores as $store) {

                if ($store->avatar == "") {

                    $img = '<img class="img-md img-avatar" src="' . asset('assets/store.png') . '" alt="Store pic" />';
                } else {

                    $img = '<img class="img-md img-avatar"  src="' . asset('uploads/files/' . $store->avatar) . '" alt="Store pic" />';

                }
                $html .= '

                        <div class="col">
                        <div class="card card-user">
                            <div class="card-header">
                                ' . $img . '
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mt-50">' . ucwords($store->name) . '</h5>
                                <div class="card-text text-muted">
                                    <p class="m-0">Store CODE: #' . $store->code . '</p>
                                    <p>' . $store->email . '</p>
                                    <a href="/store/detail/' . $store->id . '" title="View store detail" class="mt-15 text-secondary"> <i class="fas fa-eye"></i></a>
                                    <a href="/store/edit/' . $store->id . '" title="Edit store" class="mt-15 mx-2 text-info"> <i class="fas fa-edit"></i></a>
                                    
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

    public function edit($id)
    {
        $store = User::find($id);

        if ($store) {

            return view('admin.store.store_edit', compact('store'));
        } else {

            abort('404');
        }
    }

    public function update(Request $request)
    {

        try {

            $store = User::find($request->id);

            // $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $pin = mt_rand(1000000, 9999999);
            //     . mt_rand(1000000, 9999999)
            //     . $characters[rand(0, strlen($characters) - 1)];
            // $pin = substr($pin,0,8);

            if (User::where('code', $request->code)->where('id', '!=', $store->id)->exists()) {

                return response()->json(['status' => 'fail', 'msg' => "Store Code already taken!"]);

            }
            if (User::where('email', $request->email)->where('id', '!=', $store->id)->exists()) {

                return response()->json(['status' => 'fail', 'msg' => "Email already taken!"]);

            }

            $store->name = $request->name;
            $store->code = $request->code;
            $store->email = $request->email;
            $store->timing = $request->timing;
            $store->contact = $request->contact;
            $store->address = $request->address;
            $store->longitude = $request->longitude;
            $store->latitude = $request->latitude;
            $store->location = $request->location;
            $store->emirate = $request->emirate;
            $store->status = "active";
            if (isset($request->avatar) && $request->avatar != '') {
                $avatar = $request->file('avatar');
                $path = 'uploads/files/';
                $filename = rand(999, 10000000) . time() . '.' . $avatar->getClientOriginalName();
                $avatar->move($path, $filename);
                $store->avatar = $filename;
            }

            $store->save();


            return response()->json([
                'status' => 'success',
                'msg' => 'Store Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }

    }

    public function fetchState(Request $request)
    {
        $data['states'] = State::where("country_id", $request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }
    public function fetchCity(Request $request)
    {
        $data['cities'] = City::where("state_id", $request->state_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function storedetail($id)
    {
        $store = User::find($id);

        if ($store) {

            return view('admin.store.store_detail', compact('store'));
        } else {

            abort('404');
        }
    }

    public function customerCount(Request $request)
    {
        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        // $filterStatus = $request->filterStatus;
        $result = Order::query();

        if ($filterTitle != '') {
            $result = $result->where('first_name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }

        // if ($filterStatus != 'All') {

        //     $result = $result->where('status', $filterStatus);
        // }
        $count = $result->where('store_id', auth()->user()->code)->count();
        if ($count > 0) {

            return response()->json(['status' => 'success', 'data' => $count]);

        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }

    }

    public function getCustomers(Request $request)
    {

        $filterEmail = $request->filterEmail;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        // $filterStatus = $request->filterStatus;
        $result = Order::query();

        if ($filterTitle != '') {
            $result = $result->where('first_name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }

        // if ($filterStatus != 'All') {

        //     $result = $result->where('status', $filterStatus);
        // }
        
        if (Auth::user()->hasRole('Store')) {

            $customerss = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->where('store_id', auth()->user()->code)->get();
            $customers= $customerss->unique('user_id');
        } else {
            $customerss = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->where('store_id', auth()->user()->store_id)->get();
            $customers= $customerss->unique('user_id');
        }
   
        if (isset($customers) && sizeof($customers) > 0) {
            
            $html = "";
            foreach ($customers as $customer) {
                $user = User::where('id',$customer->user_id)->first();
            //    dd($user);
              
                // $totalorders ='0';
                 $totalorders = Order::where('user_id',$user->id)->where('store_id',auth()->user()->code)->count();
            // dd($totalorders);
            $total_payment = Order::where('user_id',$user->id)->where('store_id',auth()->user()->code)->sum('coupon_payment');

                
                $html .= '
                    <tr>
                        <td>' . ($customer->created_at)->isoFormat('MMM Do YYYY') . '</td>
                        <td>' . $customer->first_name . '</td>
                        <td>' . $customer->email . '</td>
                        <td>' . $customer->phone . '</td>
                        <td>' . $totalorders . '</td>
                        <td><span>AED: '.round($total_payment,2).'</span></td>
                        <td class="text-end">
                            <a href="/store/customer/detail/'.$user->id.'"
                                class="btn btn-sm btn-brand rounded font-sm mt-15">View details</a>
                        </td>
                    </tr>
                ';
            }
            return response(['status' => 'success', 'rows' => $html]);
        } else {
            return response(['status' => 'fail']);
        }

    }
    public function storeCustomer(Request $request)
    {  
       $storecustomer = User::where('id',$request->id)->first();
       $storcustome = Order::where('user_id',$storecustomer->id)->where('store_id',auth()->user()->code)->count();
       $storcustpayment = Order::where('user_id',$storecustomer->id)->where('store_id',auth()->user()->code)->sum('coupon_payment');
        $cusorder = Order::where('user_id',$storecustomer->id)->where('store_id',auth()->user()->code)->get();
        return view('store.customers.customer-detail',compact('cusorder','storecustomer','storcustome','storcustpayment'));
    }
}
