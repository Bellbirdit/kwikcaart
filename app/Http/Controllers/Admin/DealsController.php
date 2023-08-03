<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deals;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Upload;
use App\Models\Product;
use App\Models\DealProduct;

class DealsController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin/deals/create-deals', compact('products'));
    }
    public function store(Request $request)
    {

        $slug = Str::slug($request->title, '-');
        $deals = new Deals;
        $deals->title = $request->title;
        $deals->start_date = $request->start_date;
        $deals->end_date = $request->end_date;
        $deals->status = '0';
        $deals->featured = '0';
        $deals->save();
        foreach ($request->product_id as $key => $product) {
            $flash_deal_product = new DealProduct;
            $flash_deal_product->deal_id = $deals->id;
            $flash_deal_product->product_id = $product;
            $flash_deal_product->save();
        }
       
        return response()->json(['status' => 'success', 'msg' => 'deals Created Successfully']);
    }

    public function uploaddealsImg($file)
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
            $upload->user_id = "2";
            $upload->file_size = $size;
            $upload->type = "image";
            //$files=json_encode($fileArr);
            if ($upload->save()) {
                return $upload->id;
            }
        }


        return null;
    }

    public function allstoreDealsproduct(Request $request)
    {
       
        $storewise = Deals::all();
        $deals = DealProduct::find($request->id);
       
        return view('admin.deals.list-all-store-deals', compact('storewise','deals'));
    }
  
    public function updatedealProduct(Request $request)
    {
       
        try {
            $deals = DealProduct::findOrFail($request->id);
            $deals->discount_type = $request->discount_type;
            $deals->discount = $request->discount;
            $deals->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Discount Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }
    

    public function ChangeProductstatus(Request $request)
    {
        $product_status = Deals::find($request->data_id);
        $product_status->status = $request->status;
        $product_status->save();
        return response()->json(['status' => 'success', 'msg' => 'Deal status has been changed successfully']);
    }

    public function delete($id)
    {
        $deal = Deals::find($id);
        $deals = Dealproduct::where('deal_id', $deal->id);
        $deals->delete();
        if ($deals) {
            $deal->delete();
        }
        if ($deal) {
            return response()->json(['status' => 'success', 'msg' => 'Product is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Product']);
    }

 


}
