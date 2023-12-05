@if(isset($storedeals) && sizeof($storedeals)>0)
    <section class="product-tabs section-padding position-relative px-3">
        @foreach($storedeals as $storedeal)
        <div class="container">
            <div class="section-title">
                <h3>{{ ucwords($storedeal->title) }}</h3>
                <a href="{{url('store/deals/list')}}" class="btn">View More</a>
            </div>
            <div class="row row-cols-3 row-cols-sm-3 row-cols-md-6 row-cols-lg-6 ">
                <?php 
                    $storedealsproducts = App\Models\StoredealProduct::where('storedeal_id', $storedeal->id)->take('12')->get();
                ?>
                @foreach($storedealsproducts as $store_deal_product)
                <?php 
                $storeproducts = App\Models\Product::where('id', $store_deal_product->product_id)->get(); 
                
                    // $storeproducts = []; 
                    /* $products = App\Models\Product::where('id', $deal_product->product_id)->get(); */
                    if(isset($storedealsproducts->product) and !empty($storedealsproducts->product)){
                        $storeproducts[] = $storedealsproducts->product;    
                    }
                ?>
                @foreach($storeproducts as $storproduct)
                <div class="col p-0">
                    <div class="product-cart-wrap style-2">
                        <div class="product-img-action-wrap">
                            <div class="product-img">
                                <a href="/product/detail/{{ $storproduct->slug }}">
                                    <img src="{{ asset('uploads/files/'.$storproduct->getImage($storproduct->thumbnail)) }}" alt="" />
                                </a>
                            </div>
                        </div>
                        <div class="product-content-wrap">
                            <div class="deals-countdown-wrap">
                                <div class="deals-countdown" data-countdown="{{ $storedeal->end_date }}">
                                </div>
                            </div>
                            <div class="">
                                <h6><a href="/product/detail/{{ $storproduct->slug }}">{{ $storproduct->name }}</a>
                                </h6>

                                <div class="product-rate-cover">
         
                                </div>

                                <div class="product-price">
                                    <?php
                                    $priceArray = $storproduct->get_deal_price();
                                    ?>
                                    <span>AED {{ $priceArray['price'] }}</span>
                                    @if(isset($priceArray['old_price']))
                                    <span class="old-price">{{ $priceArray['old_price'] }}</span>
                                    @endif
                                </div>
                                <div class="product-card-bottom">

                                    <div class="detail-qty border radius">
                                        <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                        <input type="text" name="quantity" class="qty-val qty_value" id="qty_value" value="1" min="1">
                                        <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                    </div>
                                    <div class="add-cart">
                                        <a class="add add_cart" href="javascript:;" id="{{ $storproduct->id }}"><i class="fi-rs-shopping-cart mr-5"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @endforeach
            </div>
        </div>
        @endforeach
    </section>
    @endif
    <!-- Storewise deal end -->


    <!-- all store deals -->
    @if(isset($deals) && sizeof($deals)>0)
    <section class="product-tabs section-padding position-relative px-3">
        @foreach($deals as $deal)
        <div class="container">
            <div class="section-title style-2 wow animate__animated animate__fadeIn">
                <h3>{{ ucwords($deal->title) }}</h3>
                <a href="{{url('all/deals/list')}}" class="btn">View More</a>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                    <div class="row product-grid-4">
                        <?php $deals_product = App\Models\DealProduct::where('deal_id', $deal->id)->take('12')->get(); ?>
                        @foreach($deals_product as $deal_product)
                        <?php $products = []; 
                        /* $products = App\Models\Product::where('id', $deal_product->product_id)->get(); */
                        if(isset($deal_product->product) and !empty($deal_product->product)){
                            $products[] = $deal_product->product;    
                        }
                        
                        ?>
                        @foreach($products as $product)
                        <div class="col-lg-2 col-md-2 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">

                                        <a href="/product/detail/{{ $product->slug }}">
                                            <img class="default-img" src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}" alt="" />
                                            <img class="hover-img" src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}" alt="" />
                                        </a>


                                    </div>
                                    @if($deal_product->discount_type == 'percentage')
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot">{{$deal_product->discount}}%</span>
                                    </div>
                                    @elseif($deal_product->discount_type == 'flat')
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot">AED {{$deal_product->discount}} OFF</span>
                                    </div>
                                    @endif

                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="/product/detail/{{ $product->slug }}">{{ $product->category->name }}</a>
                                    </div>
                                    <h6><a href="/product/detail/{{ $product->slug }}">{{ $product->name }}</a> </h6>
                                    <div class="text-center detail-info col-md-5" data-title="Stock">

                                    </div>
                                    <div class="product-price">
                                        
                                        <?php
                                            $priceArray = $product->get_deal_price();
                                        ?>
                                        <span>AED {{ round($priceArray['price'],2) }}</span>
                                        @if(isset($priceArray['old_price']))
                                        <span class="old-price">{{ round($priceArray['old_price'],2) }}</span>
                                        @endif
                                    </div>
                                    <div class="product-card-bottom">
                                        <div class="detail-qty border radius">
                                            <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                            <input type="text" name="quantity" class="qty-val qty_value" id="qty_value" value="1" min="1">
                                            <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                        </div>
                                        <div class="add-cart">
                                            <a class="add add_cart" href="javascript:;" id="{{ $product->id }}"><i class="fi-rs-shopping-cart mr-5"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
    @endif