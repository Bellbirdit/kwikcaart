<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Brand;
use App\Models\Upload;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Excel;
use App\Imports\BrandsImport;

class BrandController extends Controller
{
    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'logo' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $slug = Str::slug($request->name, '-');
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->order_level = $request->order_level;
            $brand->meta_title = $request->meta_title;
            $brand->brand_code = $request->brand_code;

            $brand->meta_description = $request->meta_description;
            $logo = $request->file('logo');
            $brand->logo = $this->uploadBrandLogo($logo);
            $brand->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Brand Created Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function uploadBrandLogo($file)
    {
        if (isset($file) && $file != "") {
            $upload = new Upload();
            $path =  'uploads/files/';
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



    public function Count(Request $request){
        $filterTitle=$request->filterTitle;
        $filterLength=$request->filterLength;
        $result=Brand::query();

        if ($filterTitle !=''){
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $count = $result->count();
        if ($count>0){

            return response()->json(['status'=>'success','data'=>$count]);

        }else{
            return response()->json(['status'=>'fail','msg'=>'No Data Found']);
        }

    }
    public function list(Request $request){

        $filterTitle=$request->filterTitle;
        $filterLength=$request->filterLength;
        $result=Brand::query();
        if ($filterTitle !=''){
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $brands = $result->take($filterLength)->skip($request->offset)->orderBy('id','DESC')->get();
        if(isset($brands) && sizeof($brands)>0){
            $html = "";
            foreach($brands as $brand){
                $brimg = $brand->getImage($brand->logo);
                if($brand->top == 0){
                    $brandtop= "checked ";
                }else{
                    $brandtop= " ";
                }
                if($brand->logo==""){
                    $img ='<img class="img-fluid" height="76" src="'.asset('assets/store.png').'" alt="brand pic" />';
                }else{
                    $img ='<img src="'.asset('uploads/files/'.$brimg).'" class="img-sm img-thumbnail img-fluid" height="76"" alt="Item" />';
                }
                $html.='
                <div class="col-lg-3 col-sm-4 col-8 flex-grow-1 col-name">
                <a class="itemside" href="#">
                    <div class="info">
                        <h5 class="mb-0">Name : '.ucwords($brand->name).'</h5>
                        <p>Code : '.$brand->brand_code.'</p>
                        <p>Order Level : '.$brand->order_level.'</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-2 col-4 col-price">
                <div class="">
                <img src="'.asset('uploads/files/'.$brimg).'" class="img-sm img-thumbnail img-fluid" height="76"" alt="Item" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-1 col-4 col-status">
                <label class="switch">
                    <input type="checkbox" '.$brandtop.' data-id="' . $brand->id . '" class="brandTop">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="col-lg-3 col-sm-1 col-4 col-status">
                <a href="/brand/edit/'.$brand->id.'" class="px-1"><i class="material-icons md-edit"></i></a>
                <a id="'.$brand->id.'" href="javascript:void(0)" class="px-1 btnDelete"><i class="material-icons md-delete_forever text-danger "></i></a>
                </div>
                ';
            }
            return response(['status'=>'success','rows'=>$html]);
        }else{
            return response(['status'=>'fail']);
        }
    }


    public function brandStatus(Request $request)

    {
        $brandstatus = Brand::find($request->data_id);
        $brandstatus->top = $request->top;
        $brandstatus->save();
        return response()->json(['status'=>'success','msg'=>'Brand status has been changed successfully']);
    }

    public function edit($id){
        $brand = Brand::find($id);
        if($brand){
            return view('admin.brands.brand_edit', compact('brand'));
        }else{
            abort('404');
        }
    }
    public function update(Request $request)
    {

        try {
            $slug = Str::slug($request->name, '-');
            $brand = Brand::find($request->id);
            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->order_level = $request->order_level;
            $brand->meta_title = $request->meta_title;
            $brand->brand_code = $request->brand_code;
            $brand->meta_description = $request->meta_description;
            if ($request->logo) {
                $brand->logo = $this->uploadBrandLogo($request->logo);
            }
            $brand->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Brand Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }

    // delete document
    public function deleteDocument($id){
        $brand=Brand::find($id);
        //   $path =  'uploads/files/';
        // $currentImage=$brand->logo;
        // if($currentImage !=NULL){
        //     if (file_exists($path.$currentImage)){
        //         @unlink($path.$currentImage);
        //     }
        // }
        
        $brand->delete();
        if ($brand){
            return response()->json(['status'=>'success','msg'=>'brand is Deleted']);
        }
        return response()->json(['status'=>'fail','msg'=>'failed to delete brand']);
    }

    public function brandImport(Request $request)
    {
        try {
            $path = $request->file('file');
            Excel::import(new BrandsImport, $path);
            return response()->json(['status' => 'success', 'msg' => 'Brands imported successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

}
