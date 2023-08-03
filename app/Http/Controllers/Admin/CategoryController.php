<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Upload;
use Excel;
use App\Imports\CategoriesImport;
use Auth;
use App\Models\CategorySale;
class CategoryController extends Controller
{
    public function index()
    {

        $categories = Category::orderBy('created_at','ASC')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {

        $categories = Category::all();
        $attributes = Attribute::all();
        return view('admin.categories.create', compact('categories', 'attributes'));
    }

   public function store(Request $request)
    {

        $slug = Str::slug($request->name, '-');
        $category = new Category;
        $category->is_featured = $request->featured;
        $category->name = $request->name;
        $category->slug = $slug;
        $category->category_code = $request->category_code;
        $category->order_level = 0;
        if ($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $icon = $request->file('icon');
        $category->icon = $this->uploadCategoryImg($icon);

        $banner = $request->file('banner');
        $category->banner = $this->uploadCategoryImg($banner);

        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        if ($request->parent_id != "0") {
            $parent = Category::find($request->id);
            
         
            $categorylevel = Category::where('id',$request->parent_id)->first();
            $category->parent_id = $categorylevel->id;
            $category->level = $categorylevel->level + 1;
            
            $category->parent_level = $categorylevel->parent_level . '>' . $categorylevel->category_code;
        } else {
            $category->parent_id = null;
            $category->level = 0;
            $category->parent_level = null;
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }
        $category->save();
        $category->attributes()->sync($request->filtering_attributes);
        return response()->json(['status' => 'success', 'msg' => 'Category Created Successfully']);
    }

    public function Count(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = Category::query();

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $count = $result->count();
        if ($count > 0) {

            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    public function list(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = Category::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $categories = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'ASC')->get();
        if (isset($categories) && sizeof($categories) > 0) {
            $html = "";
            foreach ($categories as $category) {
                $parent  = Category::where('id', $category->parent_id)->first();
                $filename = $category->getImage($category->icon);
                $parentimg = $category->getImage($category->banner);
                if ($parent) {
                    $value = $parent->name;
                } else {
                    $value = "Nill";
                }
                if($category->is_featured == 1){
                    $checkbox= "checked ";
                }else{
                    $checkbox= " ";
                }
                $html .= '
                <div class="row align-items-center">
                <div class="col-lg-3 col-sm-4 col-8 flex-grow-1 col-name">
                    <a class="itemside" href="#">
                        <div class="info">
                            <h5 class="mb-0">Name : '.$category->name.'</h5>
                            <p>Code : '.$category->category_code.'</p>
                            <p>Parent : '. $category->parent_level.' </p>
                            <p>Order Level : '.$category->order_level.'</p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-sm-2 col-4 col-price">
                    <div class="">
                        <img src="' . asset('uploads/files/' . $filename) . '" class="img-sm img-thumbnail" alt="Item"style="width:50px;" />
                    </div>
                </div>
                <div class="col-lg-3 col-sm-2 col-4 col-price">
                    <div class="">
                    <img src="' . asset('uploads/files/' . $parentimg) . '"  class="" alt="Item" style="width:50px;"/>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-1 col-4 col-status">
                    <label class="switch" >
                    
                    <input type="checkbox" data-id="' . $category->id . '" class="categoryFetaured" '.$checkbox.'  >
                        <span class="slider round "></span>
                    </label>
                </div>
                <div class="col-lg-2 col-sm-1 col-4 col-status">
                    <a href="/category/edit/' . $category->id . '" class="px-1"><i class="material-icons md-edit"></i></a>
                    <a href="javascript:void(0)" class="px-1 btnDelete" id="' . $category->id . '"><i class="material-icons md-delete_forever text-danger"></i></a>
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
        $category = Category::find($id);

        if ($category) {

            return view('admin.categories.category-edit', compact('category'));
        } else {

            abort('404');
        }
    }
    public function update(Request $request)
    {

        try {
            $category = Category::find($request->id);

            $slug = Str::slug($request->name, '-');

            $category->name = $request->name;
            $category->slug = $slug;
            $category->category_code = $request->category_code;
            $category->order_level = $request->order_level;
            if ($request->icon) {
                $category->icon = $this->uploadCategoryImg($request->icon);
            }
            if ($request->banner) {
                $category->banner = $this->uploadCategoryImg($request->banner);
            }
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
         
            if ($request->parent_id != "0") {
                $parent = Category::find($request->id);
                
             
                $categorylevel = Category::where('id',$request->parent_id)->first();
                $category->parent_id = $categorylevel->id;
                $category->level = $categorylevel->level + 1;
                
                $category->parent_level = $categorylevel->parent_level . '>' . $categorylevel->category_code;
            } else {
                $category->parent_id = null;
                $category->level = 0;
                $category->parent_level = null;
            }
            if ($request->commision_rate != null) {
                $category->commision_rate = $request->commision_rate;
            }
            $category->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Category Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();
        if ($category) {
            return response()->json(['status' => 'success', 'msg' => 'Category is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Category']);
    }

    public function uploadCategoryImg($file)
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


            //$files=json_encode($fileArr);
            if ($upload->save()) {
                return $upload->id;
            }
        }


        return null;
    }


    public function ChangeCategorystatus(Request $request)

    {
  
        $category = Category::find($request->data_id);
        
        $category->is_featured = $request->is_featured;
        $category->save();
        return response()->json(['status'=>'success','msg'=>'Category status has been changed successfully']);
  
    }


    public function categoryImport(Request $request)
    {
        try {
            $path = $request->file('file');

            Excel::import(new CategoriesImport, $path);

             

            return response()->json(['status' => 'success', 'msg' => 'Categories import successfully']);
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    
        public function getSubCategory($id){

        $sub_cats = Category::where("parent_id",$id)->get();

        if(isset($sub_cats) && sizeof($sub_cats)>0){
            $html='';
            foreach($sub_cats as $cat){

                $second_sub = Category::where("parent_id",$cat->id)->get();

                if(isset($second_sub) && sizeof($second_sub)>0){

                    $secondHtml='<ul class="menu-title-sub">';

                    foreach($second_sub as $key=>$value){

                        $secondHtml.='

                            <li><a href='.route('shop').''.'/'.''.$value->id.'>'.$value->name.'</a></li>
                           
                        ';
                    }
                    $secondHtml.='</ul>';
                }else{
                    $secondHtml='';
                }

                $html.='
                
                    <div class="col-md-3">
                    <a class="menu-title" href="javascript:;">'.$cat->name.'</a>
                    '.$secondHtml.'
                </div>
                ';
            }


            return response()->json(['status'=>'success','html'=>$html]);
        }else{

            return response()->json(['status'=>'fail']);

        }
    }

    public function allCategories(Request $request)
    {
        $maincategorys = Category::where('level',1)->get();
        return view('frontend.allcategories',compact('maincategorys'));
    }
        public function viewTrending(Request $request)
    {
        $trending = CategorySale::all();
        return view ('admin.categories.trending-category');
    }
    public function trendingCount(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = CategorySale::query();

        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $count = $result->count();
        if ($count > 0) {

            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    public function trendingCategory(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = CategorySale::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        $categories = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'ASC')->get();
        if (isset($categories) && sizeof($categories) > 0) {
            $html = "";
            foreach ($categories as $category) {
             $categoryname = Category::where('id',$category->category_id)->pluck('name')->first();
                $html .= '
                <div class="row align-items-center">
                <div class="col-lg-3 col-sm-4 col-8 flex-grow-1 col-name">
                    <a class="itemside" href="#">
                        <div class="info">
                            <h5 class="mb-0">' . $categoryname . '</h5>
                        </div>
                    </a>
                   
                </div>
                <div class="col-lg-2 col-sm-1 col-4 col-status">
                    <h5 class="mb-0">' . $category->store_id . '</h5>
                </div>
                <div class="col-lg-2 col-sm-1 col-4 col-status">
                    <a href="javascript:void(0)" class="px-1 btnDelete" id="' . $category->id . '"><i class="material-icons md-delete_forever text-danger"></i></a>
                </div>
            </div>
                ';
            }
            return response(['status' => 'success', 'rows' => $html]);
        } else {
            return response(['status' => 'fail']);
        }
    }
    public function deleteTcategory($id)
    {
        {
            $tcategory = CategorySale::find($id);
            $tcategory->delete();
            if ($tcategory) {
                return response()->json(['status' => 'success', 'msg' => 'Category is Deleted']);
            }
            return response()->json(['status' => 'fail', 'msg' => 'failed to delete Category']);
        }
    }
}
