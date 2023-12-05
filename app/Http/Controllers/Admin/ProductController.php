<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Imports\ProductStorewise;
use App\Imports\UpdateProductImport;
use App\Imports\CategorySaleImport;
use App\Imports\BulkDelete;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\PickupSchedule;
use App\Models\PickupTime;
use App\Models\Product;
use App\Models\Deals;
use App\Models\StorewiseDeal;
use App\Models\Review;
use App\Models\ShippingTime;
use App\Models\StoreProducts;
use App\Models\StoreShippingSchedule;
use App\Models\Upload;
use App\Models\User;
use App\Models\Brand;
use App\Models\CategorySale;
use Auth;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Session;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function store(Request $request)
    {


        try {
            foreach($request->store_id as $store_id){
            $slug = Str::slug($request->name, '-');
            $product = new Product();
            $product->added_by = 'Admin';
            $product->user_id = '1';
            $product->name = $request->name;
            if (isset($request->product_acess) && $request->product_acess != '') {
                $product->product_acess = json_encode($request->product_acess);
            }

            $product->slug = $slug;
            $product->barcode = $request->barcode;
            $product->category_id = $request->category;
            $product->brand_id = $request->brand;
            $product->store_id = $store_id;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->vat_status = $request->vat_status;
            $product->warrenty = $request->warrenty;
            $product->published = '1';
            $product->stock = $request->stock;
            $product->price = $request->price;
            $product->shipping_cost = $request->shipping_cost;

            if (isset($request->discount) && $request->discount != '') {
                $product->discount = $request->discount;
                $product->discount_type = $request->discount_type;
            } else {
                $product->discount = null;
                $product->discount_type = null;
            }
            if (isset($request->min_order_qty) && $request->min_order_qty != '') {
                $product->min_qty = $request->min_order_qty;
            }

            if ($request->discount != null) {
                $productDiscount = $request->price * $request->discount / 100;
                $prodis = $request->price - $productDiscount;
                $product->discounted_price = $prodis;
            } else {
                $product->discounted_price = $request->price;
            }
            $product->unit = $request->unit;
            $product->unit_value = $request->unit_value;
            $product->shipping_width = $request->shipping_width;
            $product->shipping_weight = $request->shipping_weight;
            $product->shipping_height = $request->shipping_height;
            $product->featured = $request->featured;
            $product->video_link = $request->video_link;
            $product->video_provider = $request->video_provider;
            $product->express_delivery = $request->express_delivery;
            $product->colors = json_encode($request->colors);
            $product->size = json_encode($request->size);
            $product->weight = json_encode($request->weight);
            if ($request->cash_on_delivery == "1") {
                $product->cash_on_delivery = $request->cash_on_delivery;
            }
            if ($request->refundable == "1") {
                $product->refundable = $request->refundable;
            }
            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_keywords = $request->meta_keywords;
            $product->thumbnail = $this->uploadProductThumbnail($request->thumbnail);
            $product->galleryimg1 = $this->uploadProductThumbnail($request->galleryimg1);
            $product->galleryimg2 = $this->uploadProductThumbnail($request->galleryimg2);
            $product->galleryimg3 = $this->uploadProductThumbnail($request->galleryimg3);
            $product->save();
            $store_product = new StoreProducts();
            $store_product->product_id = $product->id;
            $store_product->store_id = $product->store_id;
            $store_product->stock = 'yes';
            $store_product->barcode = $product->barcode;
            $store_product->save();
            return response()->json(['status' => 'success', 'msg' => 'Product Added Successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage() . $e->getLine()]);
        }
    }

    public function uploadProductThumbnail($file)
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
            $img = \Image::make($file->getRealPath())->encode();
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
                return $upload->file_original_name;
            }
        }
        return null;
    }

    public function Count(Request $request)
    {
        $filterCode = $request->filterCode;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = Product::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterCode != '') {
            $result = $result->where('barcode', 'like', '%' . $filterCode . '%');
        }
        if ($filterStatus != 'All') {

            $result = $result->where('stock', $filterStatus);
        }
        $count = $result->count();
        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    function list(Request $request)
    {
        $filterCode = $request->filterCode;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = Product::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterCode != 'All') {
            $result = $result->where('barcode', 'like', '%' . $filterCode . '%');
        }
        if ($filterStatus != 'All') {

            $result = $result->where('stock', $filterStatus);
        }

        $products = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
        if (isset($products) && sizeof($products) > 0) {
            $html = "";
            foreach ($products as $product) {
                $procat = Category::where('id', $product->category_id)->pluck('parent_level')->first();
                $catname = Category::where('id', $product->category_id)->pluck('name')->first();
                $tprosale = OrderItem::where('product_id', $product->id)->count();
                if ($product->thumbnail == "") {
                    $img = '<img src="' . asset('assets/store.png') . '" class="img-sm img-thumbnail" alt="Item" />';
                } else {
                    $filename = $product->getImage($product->thumbnail);
                    $img = '<img class="img-md img-avatar" src="' . asset('uploads/files/' . $filename) . '" alt="product pic" />';
                }
                if ($product->stock == 'yes') {
                    $prostock = '<span class="badge rounded-pill alert-success">In Stock</span>';
                } else {
                    $prostock = '<span class="badge rounded-pill alert-danger">No Stock</span>';
                }
                if ($product->published == '1') {
                    $procheck = "checked";
                } else {
                    $procheck = ' ';
                }
                $html .= '
                                <div class="col-lg-4 col-sm-4 col-8 flex-grow-1 col-name">
                                    <a class="itemside" href="#">
                                        <div class="left">
                                            ' . $img . '
                                        </div>
                                        <div class="info">
                                            <h6 class="mb-0">' . $product->name . '</h6>
                                            <h6>Barcode : ' . $product->barcode . '</h6>
                                            <p>' . $procat . '</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-4 col-price"><del>AED' . $product->price . ' </del> <br><span>AED ' . $product->discounted_price . '</span></div>
                                <div class="col-lg-2 col-sm-2 col-4 col-price">
                                    <span>Category : ' . $catname . '</span><br>
                                    <span>No.Of Sale :' . $tprosale . '</span><br>
                                   ' . $prostock . '
                                </div>
                                <div class="col-lg-2 col-sm-1 col-4 col-status">
                                    <label class="switch">
                                    <input type="checkbox"' . $procheck . ' data-id="' . $product->id . '" class="productStock" >
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-lg-2 col-sm-1 col-4 col-status">
                                    <a  href="/product/edit/' . $product->id . '" class="px-1"><i class="material-icons md-edit"></i></a>
                                </div>
                ';
            }

            return response(['status' => 'success', 'rows' => $html]);
        } else {
            return response(['status' => 'fail']);
        }
    }
    public function ChangeProductstatus(Request $request)
    {

        $product_status = Product::find($request->data_id);
        $product_status->published = $request->published;
        $product_status->save();
        return response()->json(['status' => 'success', 'msg' => 'Product status has been changed successfully']);
    }
    public function reviews_list($id)
    {
        $reviews = Review::where('product_id', $id)->get();
        return view('admin.products.reviews_list', compact('reviews', 'id'));
    }
    
    public function getUploads(Request $request){
        $query = $request->get('q');
        $page = $request->input('page') ?? 1;
        $perPage = 10; // Number of products to return per page
        $uploads = \App\Models\BulkUpload::orderBy('id', "desc")->paginate($perPage, ['*'], 'page', $page);
        return view('admin.products.upload_lists', compact('uploads'));
    }
    
    public function edit($id)
    {
        $products = Product::find($id);
        if ($products) {
            return view('admin.products.edit', compact('products'));
        } else {
            abort('404');
        }
    }

    public function update(Request $request)
    {
        try {
            $slug = Str::slug($request->name, '-');
            $product = Product::find($request->id);
            $product->name = $request->name;
            if (isset($request->product_acess) && !empty($request->product_acess)) {
                $product->product_acess = json_encode($request->product_acess);
            }

            $product->slug = $slug;
            $product->barcode = $request->barcode;
            $product->category_id = $request->category;
            $product->brand_id = $request->brand;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->vat_status = $request->vat_status;
            $product->warrenty = $request->warrenty;
            $product->published = '1';
            $product->stock = $request->stock;
            $product->price = $request->price;
            $product->shipping_cost = $request->shipping_cost;
            if (isset($request->discount) && $request->discount != '') {
                $product->discount = $request->discount;
                $product->discount_type = $request->discount_type;
            } else {
                $product->discount = null;
                $product->discount_type = null;
            }
            if (isset($request->min_order_qty) && $request->min_order_qty != '') {
                $product->min_qty = $request->min_order_qty;
            }

            if ($request->discount != null) {
                $productDiscount = $request->price * $request->discount / 100;
                $prodis = $request->price - $productDiscount;
                $product->discounted_price = $prodis;
            } else {
                $product->discounted_price = $request->price;
            }

            $product->unit = $request->unit;
            $product->unit_value = $request->unit_value;
            $product->shipping_width = $request->shipping_width;
            $product->shipping_weight = $request->shipping_weight;
            $product->shipping_height = $request->shipping_height;
            $product->featured = $request->featured;
            $product->video_link = $request->video_link;
            $product->video_provider = $request->video_provider;
            $product->express_delivery = $request->express_delivery;
            $product->colors = json_encode($request->colors);
            $product->size = json_encode($request->size);
            $product->weight = json_encode($request->weight);
            if ($request->cash_on_delivery == "1") {
                $product->cash_on_delivery = $request->cash_on_delivery;
            }
            if ($request->refundable == "1") {
                $product->refundable = $request->refundable;
            }
            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_keywords = $request->meta_keywords;
            if ($request->thumbnail) {
                $product->thumbnail = $this->uploadProductThumbnail($request->thumbnail);
            }
            if ($request->galleryimg1) {
                $product->galleryimg1 = $this->uploadProductThumbnail($request->galleryimg1);
            }
            if ($request->galleryimg2) {
                $product->galleryimg2 = $this->uploadProductThumbnail($request->galleryimg2);
            }
            if ($request->galleryimg3) {
                $product->galleryimg3 = $this->uploadProductThumbnail($request->galleryimg3);
            }
            $product->save();
            $store_product = new StoreProducts();
            $store_product->product_id = $product->id;
            $store_product->store_id = $product->store_id;
            $store_product->stock = 'yes';
            $store_product->barcode = $product->barcode;

            $product->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Product Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }
    
    public function delete($id)
    {
        $Product = Product::find($id);
        $Product->delete();
        if ($Product) {
            return response()->json(['status' => 'success', 'msg' => 'Product is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Product']);
    }





    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
   
            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $realPath = $file->getRealPath();
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
           
            $destinationPath = storage_path('app/uploads');
            $filename = time().".".$extension;
            // $path = $destinationPath.$filename;
            
            $file->move($destinationPath, $filename);
             
            // \App\Models\BulkUpload::create([
            //     'name' => $name,
            //     'filename' => $filename,
            //     'user_id' => Auth::user()->id,
            //     'type' => 'BulkProductUpload',
            // ]);
        //   dd($path);
        //     $path = $request->file('file');
        //     $importProductsArray = Excel::toArray(new ProductImport, $path);
            
        //     // Import products from Excel file
        //     // Excel::import(new ProductImport, $path);
            
        //     // Get all products
        //     // $products = Product::all();
            
        //     foreach($importProductsArray as $importProducts){
        //         foreach($importProducts as $importProduct){    
        //             // $this->importProductStore($importProduct);    
        //         }
        //     }
        
            Excel::import(new ProductImport, $destinationPath."/".$file);
            
            foreach($importProductsArray as $importProducts){
                foreach($importProducts as $importProduct){    
                    $upload->increment('processed');   
                    $this->importProductStore($importProduct);    
                }
            }
            
            

            return response()->json(['status' => 'success', 'msg' => 'Products imported successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    
    public function importProductStore($row){
        $products = Product::where('barcode', (float)$row['barcode'])->get();
        
        $stores = User::get('code');
        $u = User::whereHas('roles', function ($q) {
            $q->where('name', 'Store');
        })->get('code');
        $r = str_replace('{"code":', '', json_encode($u));
        $code = str_replace('}', '', $r);
        
        // Loop through products
        foreach ($products as $pro) {

            Product::where('barcode', $pro->barcode)
                ->update(['stock' => $pro->stock]);


            $pr = Product::where('id', $pro->id)->first();
            $pr->store_id = $code;
            $pr->save();
            foreach ($u as $s) {
                // Check if a record with the same barcode already exists in the StoreProducts table
                $existingStoreProducts = StoreProducts::where('product_id', $pro->id)->where('store_id', $s->code)->where('barcode', $pro->barcode)->get();
                // If no record exists, create a new one for each user with a "Store" role
                if($existingStoreProducts->isEmpty()){
                    $store_product = new StoreProducts();
                    $store_product->product_id = $pro->id;
                    $store_product->store_id = $s->code;
                    $store_product->stock = $pro->stock;
                    $store_product->barcode = $pro->barcode;
                    $store_product->save();
                }else{
                    foreach($existingStoreProducts as $existingStoreProduct){
                        $existingStoreProduct->product_id = $pro->id;
                        $existingStoreProduct->store_id = $s->code;
                        $existingStoreProduct->stock = $pro->stock;
                        $existingStoreProduct->barcode = $pro->barcode;
                        $existingStoreProduct->save();
                    }    
                }
            }
        }
    }




    public function Updateimport(Request $request)
    {

        try {
            $path = $request->file('file');
            Excel::import(new UpdateProductImport, $path);
            return response()->json(['status' => 'success', 'msg' => 'Products Updated successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => 'Please update correct barcode on file', 'error'=>$e->getMessage()]);
        }
    }
    public function UpdateStoreimport(Request $request)
    {
        try {
            $path = $request->file('file');
            Excel::import(new ProductStorewise, $path);
            return response()->json(['status' => 'success', 'msg' => 'Products Updated successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => "Some thing is wrong with csv file"]);
        }
    }
    public function productBulkdelete(Request $request)
    {
        try {
            $path = $request->file('file');
            Excel::import(new BulkDelete, $path);
            return response()->json(['status' => 'success', 'msg' => 'Products Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
            ]);
        }
    }
    public function detailView(Request $request)
    {
        
       if (session::get('store_id')) {
            $store_id = session::get('store_id');
        } else {
            
            session()->put('error','Please select a store first');
         
            return redirect('/');
        }
        $today = Carbon::today();
        $currentTime = Carbon::now()->toTimeString();
      
        $products = Product::where('slug', $request->id)->first();
       $standarddate = StoreShippingSchedule::where('store_id', $store_id)
                        ->whereDate('date', '=', $today)
                        ->orderBy('date', 'asc')
                        ->first();


        if ($standarddate != '') {
            $standardtime = ShippingTime::where('date_id', $standarddate->id)->where('start_time','>=',$currentTime)->first();
        } else {
            $standardtime = '';
        }
        $pickuptimes = PickupSchedule::where('store_id', $store_id)
                        ->whereDate('date', '=', $today)
                        ->orderBy('date', 'asc')
                        ->first();
        if ($pickuptimes != '') {
            $pickuptime = PickupTime::where('date_id', $pickuptimes->id)->where('start_time','>=',$currentTime)->first();
        } else {
            $pickuptime = '';
        }
        $storeaddress = User::where('code', $store_id)->first();
        $alldeals = Deals::where('end_date', '>=', $today)->where('status', 0)->get();
        $storedeals = StorewiseDeal::where('end_date', '>=', $today)->where('status', 0)->get();
        if ($products) {
            return view('frontend.product-detail', compact('storedeals', 'alldeals', 'storeaddress', 'products', 'pickuptimes', 'pickuptime', 'standarddate', 'standardtime'));
        }
    }

    //store products
    public function storecount(Request $request)
    {
        $filterCode = $request->filterCode;
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $filterStatus = $request->filterStatus;
        $result = Product::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterCode != '') {
            $result = $result->where('barcode', 'like', '%' . $filterCode . '%');
        }
        if ($filterStatus != 'All') {
            $result = $result->where('stock', $filterStatus);
        }
        $count = $result->count();
        // return $count;
        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }
    public function storelist(Request $request)
    {
        $store_id = session::get('store_id');
        $filterCode = $request->filterCode;
        $filterTitle = $request->filterTitle;
        $filterStatus = $request->filterStatus;
        $result = Product::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }
        if ($filterCode != 'All') {
            $result = $result->where('barcode', 'like', '%' . $filterCode . '%');
        }
        if ($filterStatus != 'All') {

            $result = $result->where('stock', $filterStatus);
        }
        $products = $result->offset($request->offset)->limit($request->limit)->orderBy('id', 'DESC')->where('store_id', 'like', '%' . Auth::user()->code . '%')->get();

        if (isset($products) && sizeof($products) > 0) {
            $html = "";
            foreach ($products as $product) {

                $store_product = StoreProducts::where('product_id', $product->id)->where('store_id', Auth::user()->code)->first();
                if(!$store_product){
                    continue;
                }
                $procat = Category::where('id', $product->category_id)->pluck('parent_level')->first();
                $catname = Category::where('id', $product->category_id)->pluck('name')->first();
                $tprosale = OrderItem::where('product_id', $product->id)->count();
                if ($product->thumbnail == "") {
                    $img = '<img src="' . asset('assets/store.png') . '" class="img-sm img-thumbnail" alt="Item" />';
                } else {
                    $filename = $product->getImage($product->thumbnail);
                    $img = '<img class="img-md img-avatar" src="' . asset('uploads/files/' . $filename) . '" alt="product pic" />';
                }

                if ($store_product->stock == 'yes') {
                    $prostock = '<span class="badge rounded-pill alert-success">In Stock</span>';
                } else if ($store_product->stock != 'yes') {
                    $prostock = '<span class="badge rounded-pill alert-danger">No Stock</span>';
                }
                if ($product->published == '1') {
                    $procheck = "checked disabled";
                } else {
                    $procheck = 'disabled';
                }
                $html .= '
                <div class="col-lg-4 col-sm-4 col-8 flex-grow-1 col-name">
                    <a class="itemside" href="#">
                        <div class="left">
                            ' . $img . '
                        </div>
                        <div class="info">
                            <h6 class="mb-0">' . $product->name . '</h6>
                            <h6>Barcode : ' . $product->barcode . '</h6>
                            <p>' . $procat . '</p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-2 col-4 col-price"><del>AED' . $product->price . ' </del> <br><span>AED ' . $product->discounted_price . '</span></div>
                <div class="col-lg-3 col-sm-2 col-4 col-price">
                    <span>Category : ' . $catname . '</span><br>
                    <span>No.Of Sale :' . $tprosale . '</span><br>
                   ' . $prostock . '
                </div>
                <div class="col-lg-2 col-sm-1 col-4 col-status">
                    <label class="switch">
                    <input type="checkbox"' . $procheck . ' data-id="' . $product->id . '" class="productStock" >
                        <span class="slider round"></span>
                    </label>
                </div>
              
            ';
            }

            return response(['status' => 'success', 'rows' => $html]);
        } else {

            return response(['status' => 'fail']);
        }
    }

    public function productcompare($id)
    {

        $product = Product::where('id', $id)->first();
        $products = Product::all();
        $compare_products  = Product::where('category_id', $product->category_id)->get();
        return view('frontend.compare-product.compare', compact('products', 'product'));
    }

    public function products_compare(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        return response()->json(['status' => 'success', 'data' => $product]);
    }

    public function category_sale(Request $request)
    {
        try {
            $path = $request->file('file');
            Excel::import(new CategorySaleImport, $path);
            return response()->json(['status' => 'success', 'msg' => 'Catregory Sale added successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => 'Please update correct barcode on file']);
        }
    }
    public function autoSearch(Request $request)
    {
        $store_id = Session::get('store_id');
        $storeproduct = StoreProducts::where('store_id', $request->store_id)->where('stock', 'yes')->first();
        $storeproducts = StoreProducts::where('store_id', $store_id)->where('stock', 'yes')->pluck('product_id');
        $query = $request->get('query');
        // \DB::enableQueryLog(); // Enable query log

        $products = Product::where('name', 'like', '%' . $query . '%')
            ->where('published', 1)
            ->whereIn('id', $storeproducts)
            ->where('stock', 'yes');
        
        // Check if $storeproduct is not null before adding the barcode condition
        if ($storeproduct) {
            $products->where('barcode', $storeproduct->barcode);
        }
        
        $products = $products->get();
        
    // dd(\DB::getQueryLog()); // Show results of log

        return response()->json($products);
    }

    public function productSearch(Request $request)

    {
        $query = $request->get('q');
        $page = $request->input('page') ?? 1;
        $perPage = 10; // Number of products to return per page
        $products = Product::where('name', 'like', '%' . $query . '%')->orWhere('barcode', 'like', '%' . $query . '%')->paginate($perPage, ['*'], 'page', $page);
        return response()->json($products);
    }


    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('name', 'like', '%' . $query . '%')->orWhere('barcode', trim($query))->get();
        return response()->json($products);
    }
}
