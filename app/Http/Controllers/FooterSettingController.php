<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FooterSetting;


class FooterSettingController extends Controller
{

    public function storeFooterSetting(Request $request)
    {
        {
            try {
                $fsetting = new FooterSetting();
                $fsetting->phone = $request->phone;
                $fsetting->info_email = $request->info_email;
                $fsetting->career_email = $request->career_email;
                $fsetting->address = $request->address;
                $fsetting->facebook = $request->facebook;
                $fsetting->instagram = $request->instagram;
                $fsetting->linkedin = $request->linkedin;
                $fsetting->twitter = $request->twitter;
                $fsetting->youtube = $request->youtube;
                $fsetting->privacy_policy = $request->privacy_policy;
                $fsetting->return_policy = $request->return_policy;
                $fsetting->terms = $request->terms;
                $fsetting->contact_us = $request->contact_us;
                $fsetting->about_us = $request->about_us;
                $fsetting->terms = $request->terms;
                $fsetting->support = $request->support;
                $fsetting->save();
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Footer Created Successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'fail',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function editFootersetting($id)
    {
        $fsetting = FooterSetting::find($id);
        if ($fsetting) {
            return view('admin.setting.footer.edit-footer-setting', compact('fsetting'));
        } else {
            abort('404');
        }
    }

    public function updateFooterSetting(Request $request)
    {
        try {
            $fsetting = FooterSetting::find($request->id);
            $fsetting->phone = $request->phone;
            $fsetting->info_email = $request->info_email;
            $fsetting->career_email = $request->career_email;
            $fsetting->address = $request->address;
            $fsetting->facebook = $request->facebook;
            $fsetting->instagram = $request->instagram;
            $fsetting->linkedin = $request->linkedin;
            $fsetting->twitter = $request->twitter;
            $fsetting->youtube = $request->youtube;
            $fsetting->privacy_policy = $request->privacy_policy;
            $fsetting->return_policy = $request->return_policy;
            $fsetting->terms = $request->terms;
            $fsetting->contact_us = $request->contact_us;
            $fsetting->about_us = $request->about_us;
            $fsetting->terms = $request->terms;
            $fsetting->support = $request->support;
            $fsetting->express_delivery = $request->express_delivery;
            $fsetting->order_collect = $request->order_collect;
            $fsetting->home_delivery = $request->home_delivery;
            $fsetting->shipping_details = $request->shipping_details;
            $fsetting->safeer_plus = $request->safeer_plus;
            $fsetting->faq = $request->faq;
            $fsetting->our_stores = $request->our_stores;
            $fsetting->service_warrenty = $request->service_warrenty;

            $fsetting->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Footer Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }


}
