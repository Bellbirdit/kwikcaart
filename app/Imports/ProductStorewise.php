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
        

       
           $storeproduct= StoreProducts::where('store_id',$row['store_code'])->where('barcode',(float)$row['barcode'])->first();
           $storeproduct->stock = $row['stock'];
          
           $storeproduct ->save();
        
    }
    public function chunkSize(): int
    {
        return 1000;
    }


}
