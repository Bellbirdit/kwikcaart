<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use App\Models\CategorySale;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CategorySaleImport implements  ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    


    public function model(array $row)
    {
        
        
        $category = Category::where('category_code',$row['category_id'])->first();
       
        return new CategorySale([
            "category_id"=> empty($category) ? '':$category->id,
            "store_id" => $row['store_code'],

            
        ]);
    }
}
