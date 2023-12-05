<?php

namespace App\Console\Commands;

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
use Illuminate\Console\Command;
use App\Models\BulkUpload;
use Auth;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Session;
use Carbon\Carbon;

class UploadBulkProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $upload = BulkUpload::where('status', 0)->orderBy('id', 'asc')->first();
        $upload = BulkUpload::find(47);
        if(isset($upload) and !empty($upload)){
            $upload->update(['status' => 1]);
            $path = storage_path('app/uploads')."/".$upload->filename;
            $importProductsArray = Excel::toArray(new ProductImport, $path);

            // Import products from Excel file
            Excel::import(new ProductImport, $path);
            
            foreach($importProductsArray as $importProducts){
                foreach($importProducts as $importProduct){    
                    $upload->increment('processed');   
                    $this->importProductStore($importProduct);    
                }
            }
            $upload->update(['status' => 2]);
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
}
