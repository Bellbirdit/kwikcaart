@if(Auth::check())

<aside class="navbar-aside" id="offcanvas_aside" style="margin-top:160px;">
        <!-- <div class="aside-top">
            <a href="index.html" class="brand-wrap">
                    <img src="assets/imgs/theme/logo.svg" class="logo" alt="Nest Dashboard" />
                </a>
            <div>
                <button class="btn btn-icon btn-aside-minimize"><i
                        class="text-muted material-icons md-menu_open"></i></button>
            </div>
        </div> -->
        @if (Auth::user()->hasRole('User'))
        <nav>
            <ul class="menu-aside">
                <li class="menu-item active">
                    <a class="menu-link" href="{{url('/user/profile')}}">
                       
                        <span class="text">User Dashboard</span>
                    </a>
                </li>
             
                <li class="menu-item ">
                    <a class="menu-link" href="{{url('view/user/orders')}}">
                       
                        <span class="text">Orders</span>
                    </a>
                </li>
              
                <li class="menu-item ">
                    <a class="menu-link" href="{{url('wishlist')}}">
                       
                        <span class="text">Wishlist</span>
                    </a>
                </li>

                <!--<li class="menu-item ">-->
                <!--    <a class="menu-link" href="{{url('wallet')}}">-->
            
                <!--        <span class="text">Wallet</span>-->
                <!--    </a>-->
                <!--</li>   -->
                <li class="menu-item ">
                    <a class="menu-link" href="{{url('track-order')}}">
                        
                        <span class="text">Track Order</span>
                    </a>
                </li>

                </li>   <li class="menu-item ">
                    <a class="menu-link" href="{{url('user/ticket')}}">
                      
                        <span class="text">Support Ticket</span>
                    </a>
                </li>
                
            </ul>
            <ul class="menu-aside">


            </ul>
            <br />
            <br />
        </nav>

        @endif
    </aside>

    @endif
    @if(!Auth::check())
    <script>window.location.href = "/login";</script>
    @endif