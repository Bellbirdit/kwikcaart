<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductImport implements  ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    


    public function model(array $row)
    {
        
        $product = Product::where('barcode', (float)$row['barcode'])->first();

        // If the product already exists, skip the import
        if ($product) {
            return null;
        }
        $approved = 0;
        $user = Auth::user();
        $role = 'Admin';
        $user_id = "1";
        $category = Category::where('category_code',$row['material_group'])->first();
        $brand = Brand::where('brand_code',$row['brand'])->first();
        if($row['featured']=="no"){
            $featured = 0;
        }else{
            $featured = 1;
        }
        if($row['cash_on_delivery']=="yes"){
            $cashD = 1;
        }else{
            $cashD = 0;
        }
        $slug = Str::slug(strtolower($row['title']), '-') ;
    If($row['discount_percentage'] != NULL){
        $propriced= $row['price']*$row['discount_percentage']/100;
        $proPrice = $row['price']-$propriced;
    }else{
        $proPrice = $row['price'];
    }
  
        return new Product([
            "name" => $row['title'],
            "slug" => $slug,
            "barcode"=>(float)$row['barcode'],
            "discount" => $row['discount_percentage'],
            "price" => $row['price'],
            "discounted_price"=> $proPrice,
            "user_id"=>$user_id,
            "added_by"=>$role,
            "category_id"=> empty($category) ? '':$category->id,
            "brand_id"=> empty($brand) ? '':$brand->id,
            "thumbnail"=>(float)$row['main_image'],
            "galleryimg1"=>$row['gallery_image_1'],
            "galleryimg2"=>$row['gallery_image_2'],
            "galleryimg3"=>$row['gallery_image_3'],
            "express_delivery"=>$row['express_delivery'],
            "video_link" => $row['video_link'],
            "short_description" => $row['short_description'],
            "description" => $row['long_description'],
            "warrenty" => $row['warranty'],
            "colors"=>$row['color_variant']=="" ? '':json_encode($row['color_variant']),
            "size"=>$row['size_variant']=="" ? '':json_encode($row['size_variant']),
            "weight"=>$row['weight_variant']=="" ? '':json_encode($row['weight_variant']),
            "featured"=>$featured,
            "cash_on_delivery"=>$cashD,
            "min_qty"=>$row["minimum_order_quantity"],
            "meta_title" => $row['meta_title'],
            "meta_keywords" => $row['meta_keywords'],
            "meta_description" => $row['meta_description'],
            "meta_image" => $row['meta_image'],
            "refundable"=>$row['refundable']=="yes" ? 1:0,
            "vat_status"=>$row['vat_applicable'],
            "unit"=>$row['uom'],
            "unit_value"=>$row['unit_value'],
            // "product_acess"=>$row['product_bought_together']=="" ? '':json_encode($row['product_bought_together']),
            "shipping_height"=>$row['shipping_height'],
            "shipping_width"=>$row['shipping_width'],
            "shipping_weight"=>$row['shipping_weight']
        ]);
    }
    public function chunkSize(): int
    {
        return 5000;
    }
}
