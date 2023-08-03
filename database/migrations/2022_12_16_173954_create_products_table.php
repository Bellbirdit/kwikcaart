<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode');
            $table->double('price');
            $table->string('matrial_group_code')->nullable();
            $table->string('added_by')->default('admin');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('parent_level')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->string('thumbnail')->nullable();
            $table->string('photos')->nullable();
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->string('tags')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('origin')->nullable();
            $table->string('warranty')->nullable();
            
            $table->double('purchase_price')->nullable();
            $table->double('attributes');
            $table->mediumText('choice_options')->nullable();
            $table->mediumText('colors')->nullable();
            $table->text('veriations')->nullable();
            $table->integer('today_deal')->default(0);
            $table->integer('published')->default(0);
            $table->integer('approved')->default(1);
            $table->string('stock_visibility_state')->default('quantity');
            $table->integer('cash_on_delivery')->default(0);
            $table->string('fast_delivery')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('seller_featured')->default(0);
            $table->integer('current_stock')->default(0);
            $table->string('unit')->nullable();
            $table->string('unit_value')->nullable();
            
            $table->integer('min_qty')->default(1);
            $table->integer('low_stock_quantity')->default(0);
            $table->double('discount')->nullable();
            $table->string('discount_type')->nullable();
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_end_date')->nullable();
            $table->double('tax')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('shipping_type')->default('flat_rate');
            $table->double('shipping_cost')->default(0.00);
            $table->double('shipping_weight')->default(0.00);
            $table->double('shipping_height')->default(0.00);
            $table->double('shipping_width')->default(0.00);
            $table->integer('shipping_days')->nullable();
            $table->integer('num_of_sale')->default(0);
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('pdf')->nullable();
            $table->mediumText('slug');
            $table->integer('refundable')->default(0);
            $table->integer('digital')->default(0);
            $table->integer('auction_product')->default(0);
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
            $table->string('external_link_btn')->default('Buy Now');
            $table->integer('whole_sale_product')->default(0);
            $table->integer('vat_status')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
