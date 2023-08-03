<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Carbon\Carbon;


class CategoriesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if($row['is_featured']=="no"){
            $featured = 0;
        }else{
            $featured = 1;
        }

        $slug = Str::slug(strtolower($row['name']), '-') ;
        return new Category([
            "parent_id" => $row['parent_id'],
            "level" => $row['level'],
            "name" => $row['name'],
            "category_code" => $row['category_code'],
            "parent_level" => $row['parent_level'],
            "order_level" => $row['order_level'],
            "banner" => $row['banner'],
            "icon" => $row['icon'],
            "is_featured"=>$featured,
            "slug" => $slug,
            "meta_title" => $row['meta_title'],
            "meta_description" => $row['meta_description']
        ]);
    }
}
