<?php

namespace App\Imports;

use App\Models\StoreProducts;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;




class ProductStorewise implements ToModel, WithHeadingRow, WithChunkReading
{
   
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {       
        // \DB::enableQueryLog(); // Enable query log

            $result = \DB::table('store_products')->where('store_id', (string)$row['store_code'])->where('barcode', (string)$row['barcode'])->update(array('stock'=>$row['stock']));
        //   $storeproducts= StoreProducts::where('store_id',$row['store_code'])->where('barcode',$row['barcode'])->get();

        //   dd($row, $result, "1", \DB::getQueryLog());
        //   foreach($storeproducts as $storeproduct){
        //         $storeproduct ->save([
        //             'stock' => $row['stock']
        //         ]);    
        //   }
           
        
    }
    public function chunkSize(): int
    {
        return 1000;
    }


}
