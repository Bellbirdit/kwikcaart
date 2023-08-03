<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCustomerController extends Controller
{
    public function adminCustomers(Request $request)
    {
        return view('admin.customers.customers');
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

        $count = $result->count();
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
        $filterStatus = $request->filterStatus;
        $result = Order::query();
        if ($filterTitle != '') {
            $result = $result->where('first_name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterEmail != 'All') {
            $result = $result->where('email', 'like', '%' . $filterEmail . '%');
        }

        $customers = $result
            ->take($filterLength)
            ->skip($request->offset)
            ->orderBy('id', 'DESC')
            ->get()
            ->unique(function ($customer) {
                return $customer->user_id . '-' . $customer->store_id;
            });
        if (isset($customers) && sizeof($customers) > 0) {

            $html = "";
            foreach ($customers as $customer) {
                $user = User::where('id', $customer->user_id)->first();
                $store = User::where('code', $customer->store_id)->first();
                $totalorders = Order::where('user_id', $user->id)->where('store_id', $store->code)->count();
                $total_payment = Order::where('user_id', $user->id)->where('store_id', $store->code)->sum('coupon_payment');
                $storename = User::where('code', $customer->store_id)->pluck('name')->first();
                $html .= '
                    <tr>
                        <td width="20%">
                            <a href="#" class="itemside">
                                <div class="info pl-3">
                                    ' . ($customer->created_at)->isoFormat('MMM Do YYYY') . '
                                </div>
                            </a>
                        </td>
                        <td>' . $customer->first_name . '</td>
                        <td>' . $customer->email . '</td>
                        <td>' . $customer->phone . '</td>
                        <td>' . $storename . '</td>
                        <td>' . $totalorders . '</td>
                        <td><span>AED:' . round($total_payment, 2) . '</span></td>
                        <td class="text-end">
                            <a href="/admin/customers/' . $user->id . '' . '/' . '' . $store->code . '"
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

    public function adminCustomerdetail($id, $code)
    {
        $storecustomer = User::where('id', $id)->first();
        $store = User::where('code', $code)->first();
        $order = Order::where('user_id', $id)->where('store_id', $code)->get();
        $storcustome = Order::where('user_id', $id)->where('store_id', $code)->count();
        $storcustpayment = Order::where('user_id', $id)->where('store_id', $code)->sum('coupon_payment');
        $cusorder = Order::where('user_id', $id)->where('store_id', $code)->get();
        return view('admin.customers.admin-customer-detail', compact('cusorder', 'storecustomer', 'storcustome', 'storcustpayment'));
    }

    public function addFeedback(Request $request)
    {
        try {
            $feedback = new Feedback();
            $feedback->email = Auth::user()->email;
            $feedback->order_number = $request->order_number;
            $feedback->heading = $request->heading;
            $feedback->description = $request->description;
            $feedback->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Thanks for your feedback'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function orderFeedback(Request $request)
    {
        $feedback = Feedback::all();
        if ($feedback->count() > 0) {
            $html = "";
            foreach ($feedback as $fedback) {
                $html .= '
                <tr>
                <td>'.$fedback->email.'</td>
                    <td>'.$fedback->order_number.'</td>
                    <td>'.$fedback->heading.'</td>
                    <td>'.$fedback->description.'</td>
                    
                </tr>
                ';
            }
            return response()->json(['status' => 'success', 'rows' => $html]);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }
    
    
}
