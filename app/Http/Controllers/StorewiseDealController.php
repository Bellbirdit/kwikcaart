<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\StoredealProduct;
use App\Models\StoreProducts;
use App\Models\StorewiseDeal;
use Illuminate\Http\Request;
use App\Models\OrderItem;


class StorewiseDealController extends Controller
{
    public function create()
    {
        return view('admin/deals/create-store-deals');
    }
    public function store(Request $request)
    {

        $deals = new StorewiseDeal;
        $deals->title = $request->title;
        $deals->start_date = $request->start_date;
        $deals->end_date = $request->end_date;
        // $deals->discount = $request->discount;
        // $deals->discount_type = $request->discount_type;
        $deals->status = '0';
        $deals->featured = '0';
        $deals->store_id = $request->store_id;
        $deals->save();
        foreach ($request->products as $key => $product) {
            $storedealspro = new StoredealProduct;
            $storedealspro->storedeal_id = $deals->id;
            $storedealspro->product_id = $product;
            $storedealspro->save();
        }
        return response()->json(['status' => 'success', 'msg' => 'deals Created Successfully']);
    }
    public function get_products(Request $request)
    {
        $html = "";
        $products = StoreProducts::where('store_id', $request->store_id)->where('stock', 'yes')->get();
        foreach ($products as $pro) {
            $html .= '<option value ="' . $pro->product_id . '" >' . Product::where('id', $pro->product_id)->pluck('name')->first() . '</option> ';
        }
        return response()->json(['status' => 'success', 'html' => $html]);
    }

    public function storeDealsproduct(Request $request)
    {
        $storewise = StorewiseDeal::all();
        return view('admin.deals.list-store-deals',compact('storewise'));
    }
 
    public function ChangeProductstatus(Request $request)
    {
        $product_status = StorewiseDeal::find($request->data_id);
        $product_status->status = $request->status;
        $product_status->save();
        return response()->json(['status' => 'success', 'msg' => 'Deal status has been changed successfully']);
    }

    public function delete($id)
    {
        $deal = StorewiseDeal::find($id);
        $deals = storeDealproduct::where('storedeal_id',$deal->id);
        $deals->delete();
        if($deals){
            $deal->delete();
        }
        if ($deal) {
            return response()->json(['status' => 'success', 'msg' => 'Product is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Product']);
    }

    public function editProduct(Request $request)
    {
        try {
            $deals = storeDealproduct::findOrFail($request->id);
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
}
