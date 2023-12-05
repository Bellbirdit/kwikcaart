@extends('layout/master')

@section('title')
Kwikcaart | Products
@endsection

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
    .hide {
        display: none;
    }

    .show {
        display: block;
    }

    .clear-img {
        position: absolute;
        top: 5px;
        right: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: red;
    }

    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }

    div.ck-editor__editable {
        min-height: 300px;
    }

    .bootstrap-tagsinput {
        width: 100%;
        border: none;
        background: #f4f5f9;
    }

    .bootstrap-tagsinput .tag {
        margin-right: 2px;
        color: white !important;
        background-color: #3ab87d;
        padding: 5px 10px;
    }
</style>



<section class="content-main">
    <div class="row">
        <div class="col-10">
            <div class="content-header">
                <h2 class="content-title">Add New Product</h2>
                <!-- <div>
                    <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button>
                    <button class="btn btn-md rounded font-sm hover-up">Publich</button>
                </div> -->
            </div>
        </div>
        <div class="col-10">
            <div class="card">
                <div class="card-body">
                    <div class="row gx-5">
                        <aside class="col-lg-3 border-end">
                            <nav class="nav nav-pills flex-column mb-4 tab">
                                <a class="nav-link tablinks active" id="tablink0" aria-current="page" href="#">General</a>
                                <a class="nav-link tablinks" id="tablink1" href="#">Pricing</a>
                                <a class="nav-link tablinks" id="tablink2" id="tablink1" href="#">Images</a>
                                <a class="nav-link tablinks" id="tablink3" href="#">Variants</a>
                                <a class="nav-link tablinks" id="tablink4" href="#">Shipping & Delivery</a>
                                <a class="nav-link tablinks" id="tablink5" href="#">SEO keywords</a>
                                <a class="nav-link tablinks" id="tablink6" href="#">Frequently Bought Together</a>
                            </nav>
                        </aside>
                        <div class="col-lg-9">
                            <form id="product_form">
                                <section class="content-body p-xl-4">
                                    <div id="tab0" class="sec show">
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Product name*</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" placeholder="Type here" name="name" id="input_name" required />
                                            </div>
                                            <!-- col.// -->
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Barcode*</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" placeholder="453544655" name="barcode" id="input_barcode" required />
                                            </div>
                                            <!-- col.// -->
                                        </div>
                                     <?php $cats=App\Models\Category::all();  ?>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Category*</label>
                                            <div class="col-lg-9">
                                                <div class="custom_select ">
                                                    <select class="js-states form-control select2" name="category" id="input_category" required>
                                                        <option value="" >Choose Category</option>
                                                        @foreach($cats as $cat)
                                                        <option value="{{$cat->id}}">{{ucwords($cat->name)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                          
                                        </div>
                                  

                                        <?php
                                            
                                            $stores = App\Models\User::whereHas('roles',function($q){
                                                        $q->where('name','Store');
                                                    })->get();
                                        ?>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Store Code*</label>
                                            <div class="col-lg-9">
                                                <div class="custom_select ">
                                                    <select class="js-states form-control select2" multiple name="store_id[]" id="store_id" required>
                                                        
                                                        @foreach($stores as $cod)
                                                        <option value="{{$cod->code}}">{{ucwords($cod->code)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <?php
                                            $brands = App\Models\Brand::all();
                                        ?>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Brand</label>
                                            <div class="col-lg-9">
                                                <div class="custom_select ">
                                                    <select name="brand" id="input_brand" class="js-states form-control select2" >
                                                        <option value="" selected>Choose Brand</option>
                                                        @foreach($brands as $brand)
                                                        <option value="{{$brand->id}}">{{ucwords($brand->name)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                          
                                        </div>
                         

                                        
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Short Description</label>
                                            <div class="col-lg-9">
                                                <textarea class="form-control" name="short_description" placeholder="Type here" rows="4" id="input_short_description"></textarea>
                                            </div>
                                          
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Description</label>
                                            <div class="col-lg-9">
                                                <textarea class="form-control " name="description"placeholder="Type here" id="editor"></textarea>
                                            </div>
                                          
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Stock</label>
                                            <div class="col-lg-9">
                                                <div class="custom_select ">
                                                    <select class=" form-control" name="stock" id="input_stock">
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                       
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Warranty </label>
                                            <div class="col-lg-9">
                                                <input type="number" class="form-control" name="warrenty" placeholder="2 years" id="input_warranty"/>
                                            </div>
                                         
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Vat Status</label>
                                            <div class="col-lg-9">
                                                <div class="custom_select ">
                                                    <select class=" form-control" name="vat_status" id="input_vat_status">
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                        <option value="exempted">Exempted</option>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                        </div>

                                     
                                        <br />
                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="0">Continue to next</a>
                                    </div>
                                    <div id="tab1" class="sec hide">
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Price</label>
                                            <div class="col-lg-8">
                                                <input type="number" class="form-control" placeholder="price" name="price" id="input_price" />
                                            </div>
                                        </div>
                                   
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Discount Price</label>
                                            <div class="col-lg-5">
                                                <input type="number" class="form-control" placeholder="Offer price" name="discount" id="input_discount_price" />
                                            </div>
                                            <div class=" col-lg-3">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" name="discount_type" id="input_discount_type">
                                                        <!-- <option value="flat" selected>Flat</option> -->
                                                        <option value="percentage" >Percentage</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                          
                                        <div class="row mb-4">
                                            <label class="col-lg-6 col-form-label">Minimum Order Quantity</label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control" placeholder="1" name="min_order_qty" id="input_min_order_qty" />
                                            </div>

                                        </div>
                                        <!-- row.// -->

                                        <!-- row.// -->
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">UOM</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" name="unit" id="input_unit">
                                                        <option value="kg" selected>kg</option>
                                                        <option value="cm">Cm</option>
                                                        <option value="gm">Gm</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- col.// -->
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Unit Value</label>

                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" placeholder="0.00" name="unit_value" id="input_unit_value" />
                                            </div>
                                           
                                        </div>
                                        <!-- <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Today Deal</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" name="today_deal" id="input_today_deal">
                                                        <option value="1">Yes</option>
                                                        <option value="0" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Featured</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" name="featured" id="input_featured">
                                                        <option value="1">Yes</option>
                                                        <option value="0" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="0">Previous</a>
                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="1">Continue to next</a>

                                    </div>
                                    <div id="tab2" class="sec hide">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h4>Main Image</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-upload">
                                                    <button onclick="clearImage()" class=" clear-img">X</button>
                                                    <img src="assets/imgs/theme/upload.svg" alt="" id="frame" class="img-fluid" />
                                                    <label class="text-danger" id="error_thumbnail" style="display: none;font-size:11px;font-weight:600;">Please upload Product main image</label>
                                                    <input class="form-control" type="file" id="formFile" onchange="preview()" name="thumbnail"  />

                                                </div>
                                                <!-- <img id="frame" src="" class="img-fluid" /> -->
                                            </div>
                                        </div>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h4>Gallery Image</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-upload">
                                                    <img src="assets/imgs/theme/upload.svg" alt="" />
                                                    <input class="form-control" type="file" id="formFileMultiple" multiple name="galleryimg1"  />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-upload">
                                                    <img src="assets/imgs/theme/upload.svg" alt="" />
                                                    <input class="form-control" type="file" id="formFileMultiple" multiple name="galleryimg2"  />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-upload">
                                                    <img src="assets/imgs/theme/upload.svg" alt="" />
                                                    <input class="form-control" type="file" id="formFileMultiple" multiple name="galleryimg3"  />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Video Link</label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" placeholder="Video Link" name="video_link" id="input_video_link" />
                                            </div>
                                            <div class=" col-lg-3">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" name="video_provider" id="input_video_provider">
                                                        <option value="" selected>Select provider</option>
                                                        <option value="youtube">Youtube</option>
                                                        <option value="vimeo">Vimeo</option>
                                                        <option value="normal">Normal</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="1">Previous</a>

                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="2">Continue to next</a>
                                    </div>
                                    <div id="tab3" class="sec hide">
                                        <div class="row mb-4">
                                            <label class="mb-3 col-lg-2">Size</label>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_size" type="checkbox" value="S" id="product-cat" name="size[]" />
                                                <label class="form-check-label" for="product-cat"> S </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_size" type="checkbox" value="M" id="product-cat-1" name="size[]"/>
                                                <label class="form-check-label" for="product-cat-1"> M </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_size" type="checkbox" value="L" id="product-cat-2" name="size[]"/>
                                                <label class="form-check-label" for="product-cat-2"> L </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_size" type="checkbox" value="XL" id="product-cat-2" name="size[]"/>
                                                <label class="form-check-label" for="product-cat-2"> XL </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_size" type="checkbox" value="XXL" id="product-cat-2" name="size[]"/>
                                                <label class="form-check-label" for="product-cat-2"> XXL </label>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="mb-3 col-lg-2">Color</label>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_colors" type="checkbox" value="Red" id="product-cat" name="colors[]"/>
                                                <label class="form-check-label" for="product-cat"> Red </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_colors" type="checkbox" value="Blue" id="product-cat-1" name="colors[]"/>
                                                <label class="form-check-label" for="product-cat-1"> Blue </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_colors" type="checkbox" value="Green" id="product-cat-2" name="colors[]"/>
                                                <label class="form-check-label" for="product-cat-2"> Green </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_colors" type="checkbox" value="Yellow" id="product-cat-2" name="colors[]"/>
                                                <label class="form-check-label" for="product-cat-2"> Yellow </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_colors" type="checkbox" value="Orange" id="product-cat-2" name="colors[]"/>
                                                <label class="form-check-label" for="product-cat-2"> Orange </label>
                                            </div>
                                        </div>
                                       
                                        <div class="row mb-4">
                                            <label class="mb-3 col-lg-2">Weight</label>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="50" id="product-cat" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat"> KG </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="100" id="product-cat-1" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-1">GMS</label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="150" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2">LTR</label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="500" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> ML </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> MTR </label>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> CM </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> Feet </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> CTN </label>
                                            </div>
                                            <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> PCS </label>
                                            </div>
                                             <div class="form-check col-lg-2">
                                                <input class="form-check-input input_weight" type="checkbox" value="1" id="product-cat-2" name="weight[]"/>
                                                <label class="form-check-label" for="product-cat-2"> Each </label>
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-4">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Variant</th>
                                                        <th scope="col">Variant Price</th>
                                                        <th scope="col">Barcode</th>
                                                        <th scope="col">Images</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row"><input type="text" class="form-control" placeholder=" variant name" readonly /></th>
                                                        <td><input type="number" class="form-control" placeholder="Price" /></td>
                                                        <td><input type="number" class="form-control" placeholder="Barcode" /></td>
                                                        <td><input class="form-control" type="file" id="formFileMultiple" multiple /></td>
                                                    </tr>

                                                </tbody>
                                            </table>

                                        </div> -->

                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="2">Previous</a>

                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="3">Continue to next</a>
                                    </div>
                                    <div id="tab4" class="sec hide">
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Express Delivery</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" id="input_express_delivery" name="express_delivery">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Cash On Delivery</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" id="input_cash_on_delivery" name="cash_on_delivery">
                                                        <option value="1">Yes</option>
                                                        <option value="0" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Self Pickup</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" id="input_self_pickup" name="self_pickup">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Refundable</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" id="input_refundable" name="refundable">
                                                        <option value="1" selected>Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-4">
                                            <label class="col-lg-4 col-form-label">Free Delivery</label>
                                            <div class=" col-lg-8">
                                                <div class="custom_select">
                                                    <select class=" form-control col-lg-5" id="input_free_delivery" name="free_delivery">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row mb-4">
                                            <label class="col-lg-8 col-form-label">Delivery Days</label>
                                            <div class=" col-lg-4">
                                                <input type="number" class="form-control" placeholder="2 Days" id="input_delivery_days" name="delivery_days"/>
                                            </div>
                                        </div> -->
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h4>Shipping</h4>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-4">
                                                            <label for="product_name" class="form-label">Width</label>
                                                            <input type="text" placeholder="inch" class="form-control" id="input_shipping_width" name="shipping_width"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-4">
                                                            <label for="product_name" class="form-label">Height</label>
                                                            <input type="text" placeholder="inch" class="form-control" id="input_shipping_height" name="shipping_height"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="product_name" class="form-label">Weight</label>
                                                    <input type="text" placeholder="gam" class="form-control" id="input_shipping_weight" name="shipping_weight"/>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="product_name" class="form-label">Shipping fees</label>
                                                    <input type="text" placeholder="AED" class="form-control" id="input_shipping_cost" value="0" name="shipping_cost"/>
                                                </div>

                                            </div>
                                        </div>
                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="3">Previous</a>

                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="4">Continue to next</a>

                                    </div>
                                    <div id="tab5" class="sec hide">
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Meta Title</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" placeholder="Type here" id="input_meta_title" name="meta_title"/>
                                            </div>
                                            <!-- col.// -->
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Meta Description</label>
                                            <div class="col-lg-9">
                                                <textarea class="form-control" placeholder="Type here" rows="4" id="input_meta_description" name="meta_description"></textarea>
                                            </div>
                                            <!-- col.// -->
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-lg-3 col-form-label">Key Word</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control w-100" id="input_meta_keywords" name="meta_keywords"/>
                                            </div>
                                            <!-- col.// -->
                                        </div>

                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="4">Previous</a>

                                        <a href="javascript:;" class="btn btn-primary btnNext" data-id="5">Continue to next</a>
                                    </div>
                                    <div id="tab6" class="sec hide">
                                        <div class="row mb-4">
                                            <label class="col-lg-5 col-form-label">Product Name with Barcode</label>
                                            <div class="col-lg-7">
                                                <div class="custom_select ">
                                                <label class="form-label">Select Product</label>

                                                <select name="product_acess[]" id="products" class="form-control aiz-selectpicker select2" multiple data-placeholder="Select Products" data-live-search="true" data-selected-text-format="count" data-ajax--url="{{ route('products.search') }}">
                                                        <option value="">Select Product</option>
                                                </select>

                                                </div>
                                            </div>
                                        
                                        </div>
                                    



                                        <br />
                                        <a href="javascript:;" class="btn btn-default btnPre" data-id="5">Previous</a>

                                        <button class="btn btn-primary" id="btnSubmit" type="submit"><i class="fas fa-spinner fa-spin" style="display:none;"></i> Save</button>
                                    </div>
                                </section>
                            </form>
                            <!-- content-body .// -->
                        </div>
                        <!-- col.// -->
                    </div>
                    <!-- row.// -->
                </div>
                <!-- card body end// -->
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            closeOnSelect: true
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#products').select2({
            ajax: {
                url: $('#products').data('ajax--url'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // User's search query
                        page: params.page || 1 // Current page number
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: data.next_page_url ? true : false // Check if there are more pages to load
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 3, // Minimum number of characters for search
            escapeMarkup: function (markup) { return markup; }, // Let select2 render HTML
            templateResult: formatProduct, // Custom function to format product results
            templateSelection: formatProductSelection, // Custom function to format product selection
            closeOnSelect: false // Do not close dropdown on select
        });

        function formatProduct (product) {
            if (product.loading) {
                return product.text;
            }

            var markup = '<div class="clearfix">' +
                '<div class="float-left">' +
                '<div class="font-weight-bold">' + product.name + '</div>' +
                // '<div class="text-muted">' + product.description + '</div>' +
                '</div>' +
                '</div>';

            return markup;
        }

        function formatProductSelection (product) {
            return product.name || product.text;
        }
    });
</script>
<script src='https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js'></script>
<script>
    var thumbnail = "";
    var photos = [];
    $(document).on('click', '.btnNext', function() {

        var currentIndex = $(this).attr('data-id');
        var nextIndex = parseInt(currentIndex) + 1;
        var error = false;
        if (currentIndex == '0') {
            var inputs = [
                "input_name",
                "input_barcode",
                "input_category",
                "store_id"
            ]

            $.each(inputs, (i, v) => {
                if ($("#" + v).val()) {
                } else {
                    $("#" + v).css('border', '1px solid red');
                    setTimeout(function() {
                        $("#" + v).css('border', 'none');
                    }, 5000);
                    error = true

                }
            })

        }
        if (currentIndex == '1') {

            var inputs = [
                "input_price",
            ]

            $.each(inputs, (i, v) => {
                if ($("#" + v).val()) {
                } else {
                    $("#" + v).css('border', '1px solid red');
                    setTimeout(function() {
                        $("#" + v).css('border', 'none');
                    }, 5000);
                    error = true
                }
            })

        }

        if (currentIndex == '2') {

            if (thumbnail=="") {
                $("#error_thumbnail").css('display', 'flex');
                $("#formFile").css('border','1px solid red');
                setTimeout(function() {
                    $("#error_thumbnail").css('display', 'none');
                $("#formFile").css('border','none');

                }, 5000);
                error = true

            } 

        }


        if (error) {

                $('html,body').animate({
                    scrollTop: top
                }, 500);
                return false;

        } else {

                $(".sec").removeClass('show');
                $(".sec").addClass('hide');
                $("#tab" + nextIndex).removeClass('hide');
                $("#tab" + nextIndex).addClass('show');
                $(".tablinks").removeClass('active');
                $("#tablink" + nextIndex).addClass('active');
                return true;tablinks

        }
    });

    $(document).on('click', '.btnPre', function() {

        var index = $(this).attr('data-id');
        $(".sec").removeClass('show');
        $(".sec").addClass('hide');
        $("#tab" + index).removeClass('hide');
        $("#tab" + index).addClass('show');
        $(".tablinks").removeClass('active');
        $("#tablink" + index).addClass('active');
        return;

    })

    $(document).on('submit','#product_form',function(e){
            e.preventDefault();
            $.ajax({
                url: '/api/product/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {

                        toastr.error('Failed', response["msg"])

                    } else if (response["status"] == "success") {

                        toastr.success('Success', response["msg"])
                        $("#product_form")[0].reset();
                        window.location.href = "/product/list";
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
    });


</script>
<script>
    $("#Brand").select2({
        placeholder: "Select a programming language",
        allowClear: true
    });
    $("#Category").select2({
        placeholder: "Select a programming language",
        allowClear: true
    });
    $("#Product1").select2({
        placeholder: "Select a Product one",
        allowClear: true
    });
    $("#Product2").select2({
        placeholder: "Select a Product two",
        allowClear: true
    });
    $("#Product3").select2({
        placeholder: "Select a Product two",
        allowClear: true
    });
    $("#multiple").select2({
        placeholder: "Select a Categories",
        allowClear: true
    });
</script>
<script>
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
        thumbnail = event.target.files[0];
    }

    function clearImage() {
        document.getElementById('formFile').value = null;
        frame.src = "";
    }
</script>
<script>
    ClassicEditor.create(document.querySelector("#editor"));
    document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();
        console.log(document.getElementById("editor").value);
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(function() {
        $('input')
            .on('change', function(event) {
                var $element = $(event.target);
                var $container = $element.closest('.example');

                if (!$element.data('tagsinput')) return;

                var val = $element.val();
                if (val === null) val = 'null';
                var items = $element.tagsinput('items');

                $('code', $('pre.val', $container)).html(
                    $.isArray(val) ?
                    JSON.stringify(val) :
                    '"' + val.replace('"', '\\"') + '"'
                );
                $('code', $('pre.items', $container)).html(
                    JSON.stringify($element.tagsinput('items'))
                );
            })
            .trigger('change');
    });
</script>
<!-- new code -->
@endsection