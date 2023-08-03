<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Deals;
use App\Models\Brand;

use App\Models\Product;
use App\Models\Review;
use App\Models\StoreProducts;
use App\Models\StorewiseDeal;
use Illuminate\Http\Request;
use Session;

class ShopController extends Controller
{
    public function home(Request $request)
    {

        if (session::get('store_id')) {

            $store_id = session::get('store_id');

        }else{
             session()->put('error','Please select a store first');                       
             return redirect('/');
        }

        $categories = Category::where('order_level', '!=', 0)->orderBy('order_level', 'ASC')->get();

        $newproducts = Product::where('stock', 'yes')->latest()->take(3)->get();

        $deals = Deals::where('status', 1)->where('featured', 1)->first();
        $storedeals = StorewiseDeal::where('store_id', $store_id)->where('status', 0)->where('featured', 0)->where('end_date', '>', date('Y-m-d'))->get();

        return view('frontend.shop', compact('storedeals', 'deals', 'categories', 'newproducts'));

    }

    public function catProducts(Request $request,$id)
    
    
    {

        $category_id = $id;
        if (session::get('store_id')) {
            $store_id = session::get('store_id');
        }else{
             session()->put('error','Please select a store first');                       
             return redirect('/');
        }
        $categories = Category::where('order_level', '!=', 0)->orderBy('order_level', 'ASC')->get();
        $catpro = Category::where('id', $request->id)->first();

        $newproducts = Product::where('stock', 'yes')->latest()->take(3)->get();
        $deals = Deals::where('status', 1)->where('featured', 1)->first();
        $storedeals = StorewiseDeal::where('store_id', $store_id)->where('status', 0)->where('featured', 0)->where('end_date', '>', date('Y-m-d'))->get();
        return view('frontend.cat-product', compact('catpro','storedeals', 'deals', 'categories', 'newproducts', 'category_id'));
    }


    public function shop_categroy(Request $request, $id = null)
    {

        $category_id = $id;
        if (session::get('store_id')) {

            $store_id = session::get('store_id');

        }else{
             session()->put('error','Please select a store first');                       
             return redirect('/');
        }
        $categories = Category::where('order_level', '!=', 0)->orderBy('order_level', 'ASC')->get();
        $newproducts = Product::where('stock', 'yes')->latest()->take(3)->get();
        $deals = Deals::where('status', 1)->where('featured', 1)->first();
        $storedeals = StorewiseDeal::where('store_id', $store_id)->where('status', 0)->where('featured', 0)->where('end_date', '>', date('Y-m-d'))->get();
        return view('frontend.shop_category', compact('storedeals', 'deals', 'categories', 'newproducts', 'category_id'));

    }

    public function shop_brand(Request $request, $id = null)
    {

        $category_id = $id;
        if (session::get('store_id')) {

            $store_id = session::get('store_id');

        }else{
             session()->put('error','Please select a store first');                       
             return redirect('/');
        }
        $brandproducts = Brand::where('id', $request->id)->first();
        $categories = Category::where('order_level', '!=', 0)->orderBy('order_level', 'ASC')->get();

        $newproducts = Product::where('stock', 'yes')->latest()->take(3)->get();

        $deals = Deals::where('status', 1)->where('featured', 1)->first();
        $storedeals = StorewiseDeal::where('store_id', $store_id)->where('status', 0)->where('featured', 0)->where('end_date', '>', date('Y-m-d'))->get();

        return view('frontend.shop_brand', compact('brandproducts','storedeals', 'deals', 'categories', 'newproducts', 'category_id'));

    }
    
    public function productCategory(Request $request)
    {
  $filterLength = $request->filterLength;
    $html = "";
    $discount = "";
    $product_count = 0;
    $reqcat = Category::with('subcategories.products')->find($request->category_id);

    $categoryname = $reqcat->name; // Initialize categoryname variable

    foreach ($reqcat->products as $product) {
        $product_count = $reqcat->products()->count();
        $review = Review::where('product_id', $product->id)->where('status', 1)->pluck('rating')->first();
        $reviewcount = Review::where('product_id', $product->id)->where('status', 1)->pluck('review')->count();

        if ($review >= 1 && $review <= 5) {
            $width = $review * 20;
        } else {
            $width = 0;
        }

        $img = $product->getImage($product->thumbnail);
        $file = asset('uploads/files/' . $img);

        $img2 = $product->getImage($product->galleryimg2);
        $file2 = asset('uploads/files/' . $img2);
        if ($product->price != $product->discounted_price) {
            $discount = '<div class="product-price">
                <span>AED ' . $product->discounted_price . '</span>
                <span class="old-price">' . $product->price . '</span>
            </div>';
        } else {
            $discount = ' <div class="product-price">
                <span>AED ' . $product->discounted_price . '</span>
            </div>';
        }

       $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';

            
    
}
    foreach ($reqcat->subcategories as $reqsubcc) {
        foreach ($reqsubcc->products as $product) {
            $product_count = $reqsubcc->products()->count();
            $review = Review::where('product_id', $product->id)->where('status', 1)->pluck('rating')->first();
            $reviewcount = Review::where('product_id', $product->id)->where('status', 1)->pluck('review')->count();

            if ($review >= 1 && $review <= 5) {
                $width = $review * 20;
            } else {
                $width = 0;
            }

            $img = $product->getImage($product->thumbnail);
            $file = asset('uploads/files/' . $img);
  $img2 = $product->getImage($product->galleryimg2);
        $file2 = asset('uploads/files/' . $img2);
            if ($product->price != $product->discounted_price) {
                $discount = '<div class="product-price">
                    <span>AED ' . $product->discounted_price . '</span>
                    <span class="old-price">' . $product->price . '</span>
                </div>';
            } else {
                $discount = ' <div class="product-price">
                    <span>AED ' . $product->discounted_price . '</span>
                </div>';
            }

           $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                      <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';

                
        
    }
        foreach ($reqsubcc->subcategories as $reqsubc) {
            foreach ($reqsubc->products as $product) {
                $product_count = $reqsubc->products()->count();
                $review = Review::where('product_id', $product->id)->where('status', 1)->pluck('rating')->first();
                $reviewcount = Review::where('product_id', $product->id)->where('status', 1)->pluck('review')->count();

                if ($review >= 1 && $review <= 5) {
                    $width = $review * 20;
                } else {
                    $width = 0;
                }

                $img = $product->getImage($product->thumbnail);
                $file = asset('uploads/files/' . $img);
                $img2 = $product->getImage($product->galleryimg2);
                $file2 = asset('uploads/files/' . $img2);
                if ($product->price != $product->discounted_price) {
                    $discount = '<div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                        <span class="old-price">' . $product->price . '</span>
                    </div>';
                } else {
                    $discount = ' <div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                    </div>';
                }

               $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';

                    
            
        }
        foreach ($reqsubc->subcategories as $reqsubcc) {
        foreach ($reqsubcc->products as $product) {
            $product_count = $reqsubcc->products()->count();
            $review = Review::where('product_id', $product->id)->where('status', 1)->pluck('rating')->first();
            $reviewcount = Review::where('product_id', $product->id)->where('status', 1)->pluck('review')->count();

            if ($review >= 1 && $review <= 5) {
                $width = $review * 20;
            } else {
                $width = 0;
            }

            $img = $product->getImage($product->thumbnail);
            $file = asset('uploads/files/' . $img);
            $img2 = $product->getImage($product->galleryimg2);
            $file2 = asset('uploads/files/' . $img2);
            if ($product->price != $product->discounted_price) {
                $discount = '<div class="product-price">
                    <span>AED ' . $product->discounted_price . '</span>
                    <span class="old-price">' . $product->price . '</span>
                </div>';
            } else {
                $discount = ' <div class="product-price">
                    <span>AED ' . $product->discounted_price . '</span>
                </div>';
            }

           $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';

                
        
    }
        foreach ($reqsubcc->subcategories as $reqsubcccc) {
            foreach ($reqsubcccc->products as $product) {
                $product_count = $reqsubcccc->products()->count();
                $review = Review::where('product_id', $product->id)->where('status', 1)->pluck('rating')->first();
                $reviewcount = Review::where('product_id', $product->id)->where('status', 1)->pluck('review')->count();

                if ($review >= 1 && $review <= 5) {
                    $width = $review * 20;
                } else {
                    $width = 0;
                }

                $img = $product->getImage($product->thumbnail);
                $file = asset('uploads/files/' . $img);
                $img2 = $product->getImage($product->galleryimg2);
                $file2 = asset('uploads/files/' . $img2);
                if ($product->price != $product->discounted_price) {
                    $discount = '<div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                        <span class="old-price">' . $product->price . '</span>
                    </div>';
                } else {
                    $discount = ' <div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                    </div>';
                }

               $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';

                    
            
        }
        }
        
    }
        }
        
    }

    return response()->json(['status' => 'success', 'html' => $html, 'categoryname' => $categoryname]);
}

  



    public function product_category(Request $request)
    {
         if (session::get('store_id')) {

            $store_id = session::get('store_id');

        }
        $html = "";
        $discount = "";
        $product_count = 0;
        $storeproduct = StoreProducts::where('store_id', $store_id)
            ->where('stock', 'yes')
            ->first();
        
        $products = Product::join('store_products', 'products.barcode', '=', 'store_products.barcode')
            ->where('products.category_id', $request->category_id)
            ->where('products.stock', 'yes')
            ->where('products.published', 1)
            ->where('store_products.store_id', $store_id)
            ->where('store_products.stock', 'yes')
            ->get();
        
        $product_count = Product::join('store_products', 'products.barcode', '=', 'store_products.barcode')
            ->where('products.category_id', $request->category_id)
            ->where('store_products.store_id', $store_id)
            ->where('store_products.stock', 'yes')
            ->count();
        
        foreach ($products as $product) {
      
  
            $review = Review::where('product_id',$product->id)->where('status',1)->pluck('rating')->first();
            $reviewcount = Review::where('product_id',$product->id)->where('status',1)->pluck('review')->count();
            if ($review== '1') {
                $width = 20;
            } elseif ($review== '2') {
                $width = 40;
            } elseif ($review== '3') {
                $width = 60;
            } elseif ($review== '4') {
                $width = 80;
            } elseif ($review== '5') {
                $width = 100;
            }else{
                $width= 0;
            }
            
            
            $img = $product->getImage($product->thumbnail);

            $file = asset('uploads/files/' . $img);
            $img2 = $product->getImage($product->galleryimg2);
            $file2 = asset('uploads/files/' . $img2);
            if ($product->price != $product->discounted_price ) {
                $discount = '<div class="product-price">
                <span>AED ' . $product->discounted_price . '</span>
                <span class="old-price">' . $product->price . '</span>
                </div>';
                    } else {
                        $discount = ' <div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                </div>';
                            }

             $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file2 . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';
        
    }
        
        return response()->json(['status' => 'success', 'html' => $html, 'product_count' => $product_count]);
    }


    public function product_brand(Request $request)
    {
        $html = "";
        $discount = "";
        $product_count = 0;
        $products = Product::where('brand_id', $request->category_id)->get();
        $product_count = Product::where('brand_id', $request->category_id)->count();

        foreach ($products as $product) {
              $review = Review::where('product_id',$product->id)->where('status',1)->pluck('rating')->first();
                $reviewcount = Review::where('product_id',$product->id)->where('status',1)->pluck('review')->count();
            if ($review== '1') {
                $width = 20;
            } elseif ($review== '2') {
                $width = 40;
            } elseif ($review== '3') {
                $width = 60;
            } elseif ($review== '4') {
                $width = 80;
            } elseif ($review== '5') {
                $width = 100;
            }else{
                $width= 0;
            }
     
            
            $img = $product->getImage($product->thumbnail);

            $file = asset('uploads/files/' . $img);

            if ($product->price != $product->discounted_price ) {
                $discount = '<div class="product-price">
                <span>AED ' . $product->discounted_price . '</span>
                <span class="old-price">' . $product->price . '</span>
                </div>';
                    } else {
                        $discount = ' <div class="product-price">
                        <span>AED ' . $product->discounted_price . '</span>
                </div>';
            }

              $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                   <div class="product-action-1 top-left">
                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                            href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                    </div>
                        <div class="product-action-1 top-right add-cart">
                        <a aria-label="Add To Cart" class="action-btn add add_cart"
                            href="javascript:;"  id="' . $product->id . '"><i class="fi-rs-shopping-cart"style="color:red;" ></i></a>
                    </div>
                    <div class="product-img product-img-zoom">
                        <a href=/product/detail/' . $product->slug . '>
                            <img class="default-img"
                                src="' . $file . '"
                                alt="" />
                            <img class="hover-img"
                                src="' . $file . '"
                                alt="" />
                        </a>
                    </div>
                </div>
                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href=/category/' . $product->category->slug . '>' . $product->category->name . '</a>
                    </div>
                    <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ' . $width . '%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (' . $reviewcount . ')</span>
                    </div>
                    <div class="product-card-bottom">
                        ' . $discount . '
                                     <div class="detail-qty border radius">
                                    <a href="#" class="qty-down" id="qty_down' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val input-qty" id="qty_val' . $product->id . '" data-id="' . $product->id . '" value="1" min="1">
                                    <a href="#" class="qty-up" id="qty_up' . $product->id . '" data-id="' . $product->id . '"><i class="fi-rs-plus"></i></a>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>';
        }
        return response()->json(['status' => 'success', 'html' => $html, 'product_count' => $product_count]);
    }

    public function shop_products(Request $request)
    {
        $html = "";
        $discount = "";
        $product_count = 0;
         if (session::get('store_id')) {

            $store_id = session::get('store_id');

        }else{
             session()->put('error','Please select a store first');                       
             return redirect('/');
        }
        $product_count = StoreProducts::where('store_id', $store_id)->where('stock', 'yes')->count();
        $allproducts = StoreProducts::where('store_id', $store_id)->where('stock', 'yes')->get();

        foreach ($allproducts as $pro) {
            $products = Product::where('id', $pro->product_id)->where('stock', 'yes')->where('published', 1)->get();

            foreach ($products as $product) {
                
            $review = Review::where('product_id',$product->id)->where('status',1)->pluck('rating')->first();
            $reviewcount = Review::where('product_id',$product->id)->where('status',1)->pluck('review')->count();
            if ($review== '1') {
                $width = 20;
            } elseif ($review== '2') {
                $width = 40;
            } elseif ($review== '3') {
                $width = 60;
            } elseif ($review== '4') {
                $width = 80;
            } elseif ($review== '5') {
                $width = 100;
            }else{
                $width= 0;
            }
                $img = $product->getImage($product->thumbnail);
                $img2 = $product->getImage($product->galleryimg1);

                $file = asset('uploads/files/' . $img);
                $file2 = asset('uploads/files/' . $img2);

                if ($product->price != $product->discounted_price ) {
                                $discount = '<div class="product-price">
                                <span>AED ' . $product->discounted_price . '</span>
                                <span class="old-price">' . $product->price . '</span>
                                </div>';
                                    } else {
                                        $discount = ' <div class="product-price">
                                        <span>AED ' . $product->discounted_price . '</span>
                                </div>';
                            }

                $html .= '<div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href=/product/detail/' . $product->slug . '>
                                                <img class="default-img"
                                                    src="' . $file . '"
                                                    alt="" />
                                                <img class="hover-img"
                                                    src="' . $file2 . '"
                                                    alt="" />
                                            </a>
                                        </div>
                                        <div class="product-action-1">
                                            <a aria-label="Add To Wishlist" class="action-btn add-wishlist"
                                                    href="javascript:;"  id=' . $product->id . '><i class="fi-rs-heart"></i></a>
                                            
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href=/product/detail/' . $product->slug . '>' . $product->category->name . '</a>
                                        </div>
                                        <p><a href=/product/detail/' . $product->slug . '>' . $product->name . '</a></p>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: '.$width.'%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> ('.$reviewcount.')</span>
                                        </div>
                                        <!-- <div>
                                    <span class="font-small text-muted">By <a
                                            href="vendor-details-1.html">Tyson</a></span>
                                </div> -->
                                        <div class="product-card-bottom">
                                            ' . $discount . '
                                            <div class="add-cart">
                                                <a class="add add_cart"
                                                    href="javascrip:;" id="' . $product->id . '"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
        }
        return response()->json(['status' => 'success', 'html' => $html, 'product_count' => $product_count]);
    }

}
