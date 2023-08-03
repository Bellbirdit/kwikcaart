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
use DB;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UpdateProductImport implements  ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    


    public function model(array $row)
    {
        If($row['discount_percentage'] != NULL){
            $propriced= $row['price']*$row['discount_percentage']/100;
            $proPrice = $row['price']-$propriced;
    
        }else{
            $proPrice = $row['price'];
        }
        $row['published'] = $row['published']??"";
        if($row['published'] == 'yes')
        {
            $publish = 1; 
        }else{
            $publish = 0;
        }
            $product = Product::where('barcode',(float)$row['barcode'])->first();
            if($product){
                $product->barcode = (float)$row['barcode'];
                $product->stock = $row['stock'];
                $product->price = $row['price'];
                $product->published = $publish;
                $product->discounted_price=$proPrice;
                $product->save();    
            }
        
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
