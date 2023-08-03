<?php

namespace App\Imports;
use App\Models\Product;
use App\Models\StoreProducts;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BulkDelete implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = Product::where('barcode', (float) $row['barcode'])->first();
        if($product){
            $sproducts = StoreProducts::where('barcode', $product->barcode);
            $product->delete();
            $sproducts->delete();           
        }
    }
}
