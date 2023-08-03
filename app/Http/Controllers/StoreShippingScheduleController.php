<?php

namespace App\Http\Controllers;
use App\Models\StoreShippingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShippingTime;


class StoreShippingScheduleController extends Controller
{
    public function storeshippingDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $shipping_dtae = new StoreShippingSchedule();
            $shipping_dtae->date = $request->date;
            $shipping_dtae->store_id = auth()->user()->code;
            $shipping_dtae->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Date Added Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function storeshippingTime(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required',
                'end_time' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $shipping_time = new ShippingTime();
            $shipping_time->date_id = $request->date_id;
            $shipping_time->start_time = $request->start_time;
            $shipping_time->end_time = $request->end_time;
            $shipping_time->count = $request->count;
            $shipping_time->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Time Added Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function deleteSschedule($id)
    {
        $schedualdate = StoreShippingSchedule::find($id);
        $stime = ShippingTime::where('date_id',$schedualdate->id);
        $stime->delete();
        if($stime){
            $schedualdate->delete();
        }
        if ($schedualdate) {
            return response()->json(['status' => 'success', 'msg' => 'Date and time is deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete date and time']);
    }
}
