<?php

namespace App\Imports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
class BrandsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $slug = Str::slug(strtolower($row['name']), '-') ;
        return new Brand([
            "name" => $row['name'],
            "logo" => $row['logo'],
            "brand_code" => $row['brand_code'],
            "slug" => $slug,
            "order_level" => $row['order_level'],
            "meta_title" => $row['meta_title'],
            "meta_description" => $row['meta_description']
        ]);
    }
}
