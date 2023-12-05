@if(Auth::check())

    <aside class="navbar-aside" id="offcanvas_aside">
        <div class="aside-top">
            <a href="{{ url('dashboard') }}" class="brand-wrap">
                <img src="{{ asset('assets/imgs/theme/logo.png') }}" class="logo"
                    alt="Safeer Dashboard" />
            </a>
            <div>
                <button class="btn btn-icon btn-aside-minimize"><i
                        class="text-muted material-icons md-menu_open"></i></button>
            </div>
        </div>
        <nav>

            @if(Auth::user()->hasRole('Admin'))

                <style>
                    .new_menu {
                        color: #6c757d;
                        padding: 5px 0 5px 15px;
                        -webkit-transition-duration: 0.3s;
                        transition-duration: 0.3s;
                        position: relative;
                        margin-left: 5px;
                    }

                </style>

                <ul class="menu-aside">
                    
                    <li class="menu-item active">
                        <a class="menu-link" href="{{ url('dashboard') }}">
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('product/list') }}">
                            <span class="text">All Products</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('category/list') }}">
                            <span class="text">Categories</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('brand/list') }}">
                            <span class="text">Brands</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('bulk/upload') }}">
                            <span class="text">Bulk Import/Export</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{url('product/reviews')}}">
                            <span class="text">Product Reviews</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/orders') }}">
                            <span class="text">Orders</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/feedback') }}">
                            <span class="text">Order Feedback</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('admin/customers') }}">
                            <span class="text">Customers</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/store/list') }}">
                            <span class="text">All Stores</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('coupon/view') }}">
                            <span class="text">Coupons</span>
                        </a>
                    </li>
                    <!--<li class="menu-item">-->
                    <!--    <a class="menu-link" href="#">-->
                    <!--        <span class="text">Newsletters</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('upload/list') }}">
                            <span class="text">Media file</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/role/view') }}">
                            <span class="text">Roles</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/admin/staff') }}">
                            <span class="text">Staffs</span>
                        </a>
                    </li>
                 
                    <!--<li class="menu-item">-->
                    <!--    <a class="menu-link" href="#">-->
                    <!--        <span class="text">Support</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('all/deals') }}">
                            <span class="text">All Store Deals</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('store/deals') }}">
                            <span class="text">Store Wise Deals</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('trending/categories') }}">
                            <span class="text">Trending Categories</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('order/report') }}">
                            <span class="text">Reports</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('web-settings') }}">
                            <span class="text">Settings</span>
                        </a>
                    </li>
                </ul>
                  

            @elseif(auth()->user()->type == '1')
                <style>
                    .new_menu {
                        color: #6c757d;
                        padding: 5px 0 5px 15px;
                        -webkit-transition-duration: 0.3s;
                        transition-duration: 0.3s;
                        position: relative;
                        margin-left: 5px;
                    }

                </style>

                <ul class="menu-aside">

                    <li class="menu-item active">
                        <a class="menu-link" href="{{ url('dashboard') }}">
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    @can('view_products')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('product/list') }}">
                            <span class="text">All Products</span>
                        </a>
                    </li>
                    @endcan
                    @can('view_categories')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('category/list') }}">
                            <span class="text">Categories</span>
                        </a>
                    </li>
                    @endcan
                    @can('view_brand')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('brand/list') }}">
                            <span class="text">Brands</span>
                        </a>
                    </li>
                     @endcan
                    @can('manage_bulkimport')
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('bulk/upload') }}">
                            <span class="text">Bulk Import/Export</span>
                        </a>
                    </li>
                      @endcan
                    @can('manage_productreviews')
                    <li class="menu-item">
                        <a class="menu-link" href="{{url('product/reviews')}}">
                            <span class="text">Product Reviews</span>
                        </a>
                    </li>
                     @endcan
                    @can('view_orders')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/orders') }}">
                            <span class="text">Orders</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/feedback') }}">
                            <span class="text">Order Feedback</span>
                        </a>
                    </li>
                    @endcan
                    @can('view_customers')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('admin/customers') }}">
                            <span class="text">Customers</span>
                        </a>
                    </li>
                    @endcan
                    @can('manage_stores')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/store/list') }}">
                            <span class="text">All Stores</span>
                        </a>
                    </li>
                    @endcan
                    @can('manage_coupons')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('coupon/view') }}">
                            <span class="text">Coupons</span>
                        </a>
                    </li>
                    @endcan
                    @can('manage_newsletter')
                    <li class="menu-item">
                        <a class="menu-link" href="#">
                            <span class="text">Newsletters</span>
                        </a>
                    </li>
                     @endcan
                    @can('manage_mediafile')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('upload/list') }}">
                            <span class="text">Media file</span>
                        </a>
                    </li>
                    
                    <!--<li class="menu-item">-->
                    <!--    <a class="menu-link" href="{{ url('/role/view') }}">-->
                    <!--        <span class="text">Roles</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                     @endcan
                    @can('manage_staff')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/admin/staff') }}">
                            <span class="text">Staffs</span>
                        </a>
                    </li>
                    @endcan
                    @can('manage_alldeals')

                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('all/deals') }}">
                            <span class="text">All Store Deals</span>
                        </a>
                    </li>
                    @endcan
                    @can('manage_storedeals')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('store/deals') }}">
                            <span class="text">Store Wise Deals</span>
                        </a>
                    </li>
                    @endcan
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('trending/categories') }}">
                            <span class="text">Trending Categories</span>
                        </a>
                    </li>
                    @can('manage_reports')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('order/report') }}">
                            <span class="text">Reports</span>
                        </a>
                    </li>
                    @endcan
                    @can('web-settings')
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('web-settings') }}">
                            <span class="text">Settings</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            @elseif(Auth::user()->hasRole('Store'))
                <ul class="menu-aside">
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('dashboard') }}">
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('/role/view') }}">
                            <span class="text">Roles</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('view/store/orders') }}">
                            <span class="text">Orders</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('view/store/cancel/orders') }}">
                            <span class="text">Cancel Order </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('view/store/products') }}">
                            <span class="text"> Products </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/customers') }}">
                            <span class="text"> Customer </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/staff') }}">
                            <span class="text"> Staff </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/shipping/schedule') }}">
                            <span class="text"> Shipping Schedule </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/pickup/schedule') }}">
                            <span class="text"> Store Pickup Schedule </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/store/ticket') }}">
                            <span class="text"> Support Ticket </span>
                        </a>
                    </li>
                </ul>
                <hr />

            @elseif(auth()->user()->type == '2')


                <ul class="menu-aside">
                    @can('view_dashboards')
                        <li class="menu-item ">
                            <a class="menu-link" href="{{ url('dashboard') }}">
                                <span class="text">Dashboard</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('/role/view') }}">
                            <span class="text">Roles</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('view/store/orders') }}">
                            <span class="text">Orders</span>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a class="menu-link" href="{{ url('view/store/cancel/orders') }}">
                            <span class="text">Cancel Order </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('view/store/products') }}">
                            <span class="text"> Products </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/customers') }}">
                            <span class="text"> Customer </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/staff') }}">
                            <span class="text"> Staff </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/shipping/schedule') }}">
                            <span class="text"> Shipping Schedule </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/pickup/schedule') }}">
                            <span class="text"> Store Pickup Schedule </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ url('/store/ticket') }}">
                            <span class="text"> Support Ticket </span>
                        </a>
                    </li>
                </ul>
            @endif
            <br />
        </nav>
    </aside>

@endif

@if(!Auth::check())
    <script>
        window.location.href = "/login";

    </script>
@endif
