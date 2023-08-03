<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySale extends Model
{
    use HasFactory;
    protected $fillable = [
       'category_id',
       'store_id'
    ];

    protected $table = 'category_sales';
}
