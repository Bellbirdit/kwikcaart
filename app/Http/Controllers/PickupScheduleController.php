<?php

namespace App\Http\Controllers;

use App\Models\PickupSchedule;
use Illuminate\Http\Request;
use App\Models\PickupTime;
use Illuminate\Support\Facades\Validator;


class PickupScheduleController extends Controller
{
    public function storepickupDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $pickup_date = new PickupSchedule();
            $pickup_date->date = $request->date;
            $pickup_date->store_id = auth()->user()->code;
            $pickup_date->save();
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

    public function storepickupTime(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required',
                'end_time' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $pickup_time = new PickupTime();
            $pickup_time->date_id = $request->date_id;
            $pickup_time->start_time = $request->start_time;
            $pickup_time->end_time = $request->end_time;
            
            $pickup_time->count =$request->count;
            $pickup_time->save();
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
    public function deletePickupdate($id)
    {
        $pickdate = PickupSchedule::find($id);
        $picktime = PickupTime::where('date_id',$pickdate->id);
        $picktime->delete();
        if($picktime){
            $pickdate->delete();
        }
        if ($pickdate) {
            return response()->json(['status' => 'success', 'msg' => 'Date and time is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete date and time']);
    }
}
