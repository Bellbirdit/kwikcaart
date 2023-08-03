<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;


use Auth;

class ProfileController extends Controller



{

    public function getProfile()
    {
       $profile= User::where('id',auth::user()->id)->get();
       return view('user/dashboard/dashboard', compact('profile'));
    }

    public function editprofile($id)
    {
        $profile = User::find($id);
        if ($profile) {
            return view('user.profile.edit-profile', compact('profile'));
        } else {
            abort('404');
        }
    }

    public function updateProfile(Request $request)
    {

        try {

            $profile = User::find(auth()->user()->id);
            $profile->name = $request->name;
            $profile->email = $request->email;
            $profile->contact = $request->contact;
            $profile->address = $request->address;
    

            if (isset($request->avatar) && $request->avatar != '') {
                $avatar = $request->file('avatar');
                $path =  'uploads/files/';
                $filename = rand(999, 10000000) . time() . '.' . $avatar->getClientOriginalName();
                $avatar->move($path, $filename);
                $profile->avatar = $filename;
            }

            $profile->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Profile Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }

        
    }

    public function ChangeAddresstatus(Request $request)
    {

        $default = UserAddress::find($request->data_id);
        $default->is_default = $request->is_default;
        $default->save();
        return response()->json(['status' => 'success', 'msg' => 'Default adrress updated successfully']);
    }

}
