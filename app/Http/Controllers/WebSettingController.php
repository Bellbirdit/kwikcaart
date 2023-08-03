<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\WebSetting;
use App\Models\WebPage;
use Auth;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\Slider;

class WebSettingController extends Controller
{
    
    public function Setting()
    {
        return view('admin/setting/index');
    }

    public function editDeliveryCharje($id)
    {
        $charje = WebSetting::where('id', $id)->first();
        if ($charje) {
            return response()->json(['status' => 'success', 'data' => $charje]);
        }
        return response()->json(['status' => 'fail']);
    }

    // Update Delivery Charges
    public function updateDeliveryCharje(Request $request)
    {
        $charje = WebSetting::where('id', $request->id)->first();
        $charje->express_delivery = $request->express_delivery;
        $charje->save();
        if ($charje) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }

    public function editVat($id)
    {
        $vat = WebSetting::where('id', $id)->first();
        if ($vat) {
            return response()->json(['status' => 'success', 'data' => $vat]);
        }
        return response()->json(['status' => 'fail']);
    }

    public function updateVat(Request $request)
    {
        $vat = WebSetting::where('id', $request->id)->first();
        $vat->vat = $request->vat;
        $vat->save();
        if ($vat) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }
    public function editStandardCharje($id)
    {
        $standard = WebSetting::where('id', $id)->first();
        if ($standard) {
            return response()->json(['status' => 'success', 'data' => $standard]);
        }
        return response()->json(['status' => 'fail']);
    }

    // Update Delivery Charges
    public function updateStandardCharje(Request $request)
    {
        $standard = WebSetting::where('id', $request->id)->first();
        $standard->standard_delivery = $request->standard_delivery;
         $standard->delivery_limit = $request->delivery_limit;
         $standard->delivery_applicable = $request->delivery_applicable;
         
        $standard->save();
        if ($standard) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }
    public function addhomeSlider(Request $request)
    {
        try {

            $heroslider = new Slider();
            $heroslider->text = $request->text;
            $heroslider->heading = $request->heading;
            $heroslider->slider = $this->uploadHomepageImg($request->slider);

            $heroslider->save();

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

    public function listhomeslider(Request $request)
    {

        $homeslider = Slider::all();

        if (isset($homeslider) && sizeof($homeslider) > 0) {
            $html = "";
            foreach ($homeslider as $homeslide) {
                $img = $homeslide->getImage($homeslide->slider);
                if ($homeslide->slider == "") {

                    $img = '<img class="img-fluid" height="76" src="' . asset('assets/no_image.jpg') . '" alt="upload pic" />';
                } else {
                    $img = '<img class="img-fluid" height="76" src="' . asset('uploads/files/' . $img) . '" alt="upload pic" />';
                }
                $html .= '
            <div class="col">
            <figure class="card border-1">

                <div class="card-header bg-white text-center mt-0 pt-0 px-2">
                    <div class="text-end">
                        <div class="dropdown px-0 m-0">
                            <a href="#" data-bs-toggle="dropdown" class="font-sm"> <i
                                    class="material-icons md-more_horiz"></i> </a>
                            <div class="dropdown-menu">
                                <a href="/slider/edit/' . $homeslide->id . '" class=" dropdown-item ">Edit</a>
                                <a class="dropdown-item text-danger btnDelete" id="' . $homeslide->id . '" href="javascript:void(0)">Delete</a>
                            </div>
                        </div>
                    </div>
                    ' . $img . '
                </div>
               

            </figure>
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
        $homeslider = Slider::find($id);

        if ($homeslider) {

            return view('admin.setting.edit-slider', compact('homeslider'));
        } else {

            abort('404');
        }
    }

    public function updateHomepage(Request $request)
    {
        try {
            $homepage = Slider::find($request->id);

            $homepage->heading = $request->heading;
            $homepage->text = $request->text;
        
            if ($request->slider) {
                $homepage->slider = $this->uploadHomepageImg($request->slider);
            }
         
            $homepage->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Data Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }


    public function deleteHomeslider($id)
    {
        $hoslider=Slider::find($id);
        $hoslider->delete();
        if ($hoslider){
            return response()->json(['status'=>'success','msg'=>'Slider deleted successfully']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete slider']);
    }


    public function uploadHomepageImg($file)
    {

        if (isset($file) && $file != "") {
            $upload = new Upload();
            $path = 'uploads/files/';
            $imgHash = time() . md5(rand(0, 10));
            $arr = explode('.', $file->getClientOriginalName());
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }
            $img = Image::make($file->getRealPath())->encode();
            $height = $img->height();
            $width = $img->width();
            if ($width > $height && $width > 1500) {
                $img->resize(1500, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } elseif ($height > 1500) {
                $img->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $ext = "webp";
            $filename = $imgHash . "." . $ext;
            $img->save($path . $filename);
            $size = $img->filesize();
            $upload->extension = $ext;
            $upload->file_name = $filename;
            $upload->user_id = Auth::user()->id;
            $upload->file_size = $size;
            $upload->type = "image";
            if ($upload->save()) {
                return $upload->id;
            }
        }

        return null;
    }

    public function createPage(Request $request)
    {
        return view('admin.setting.pages.create');
    }

    public function storePage(Request $request)
    {
        {

            try {
             
                $slug = Str::slug($request->title, '-');
                $newpage = new WebPage();
                $newpage->title = $request->title;
                $newpage->slug = $slug;
                $newpage->content = $request->content;
                $newpage->save();
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Page Created Successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'fail',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }
    public function allPages(request $request)
    {
        $pages = WebPage::all();
        return view('admin.setting.pages.pages',compact('pages'));
    }

    public function show_custom_page($slug){
        $page = Webpage::where('slug', $slug)->first();
        if($page != null){
            return view('admin.setting.pages.custom-page', compact('page'));
        }
        abort(404);
    }

    public function editPage($id)
    {
        $pagedata = WebPage::find($id);

        if ($pagedata) {

            return view('admin.setting.pages.edit', compact('pagedata'));
        } else {

            abort('404');
        }
    }
    public function UpdatePage(Request $request)
    {
        {
            try {
                $slug = Str::slug($request->title, '-');
                $pagedata = WebPage::find($request->id);
    
                $pagedata->title = $request->title;
                $pagedata->slug = $slug;
                $pagedata->content = $request->content;
            
             
                $pagedata->save();
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Data Updated Successfully',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'fail',
                    'msg' => $e->getMessage(),
                ]);
            }
        }
    }

    public function deletePage($id)
    {
        $pagedata=WebPage::find($id);
        $pagedata->delete();
        if ($pagedata){
            return response()->json(['status'=>'success','msg'=>'Page deleted successfully']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete page']);
    }
}

