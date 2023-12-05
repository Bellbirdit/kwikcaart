@extends('frontend/layout/master')
@section('title')
Kwikcaart | User Dashboard
@endsection
@section('frontend/content')

<!--<link href="{{ asset('assets/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />-->



<div class="container-fluid">

    <!-- <div class="page-header breadcrumb-wrap">
        <div class="container-fluid">
            <div class="breadcrumb">
                <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Pages <span></span> My Account
            </div>
        </div>
    </div> -->
    <div class="page-content pt-30 pb-30 mt-3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="dashboard-menu">
                                <ul class="nav flex-column" role="tablist">
                             
                                    <li class="nav-item">
                                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard"
                                            role="tab" aria-controls="dashboard" aria-selected="false">My Account</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                            href="#profile" role="tab" aria-controls="profile"
                                            aria-selected="false">Profile</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="return-request-tab" data-bs-toggle="tab"
                                            href="#return-request" role="tab" aria-controls="return-request"
                                            aria-selected="false">Return Request</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist"
                                            role="tab" aria-controls="wishlist" aria-selected="false">Wishlist</a>
                                    </li>
                                    <!--<li class="nav-item">-->
                                    <!--    <a class="nav-link" id="wallet-tab" data-bs-toggle="tab" href="#wallet"-->
                                    <!--        role="tab" aria-controls="wallet" aria-selected="false"> Wallet</a>-->
                                    <!--</li>-->
                                    <!--<li class="nav-item">-->
                                    <!--    <a class="nav-link" id="safeer-plus-point-tab" data-bs-toggle="tab"-->
                                    <!--        href="#safeer-plus-point" role="tab" aria-controls="safeer-plus-point"-->
                                    <!--        aria-selected="false">Safeer Plus Point</a>-->
                                    <!--</li>-->
                                    <li class="nav-item">
                                        <a class="nav-link" id="track-orders-tab" data-bs-toggle="tab"
                                            href="#track-orders" role="tab" aria-controls="track-orders"
                                            aria-selected="false">Track Your Order</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="support-tab" data-bs-toggle="tab" href="#support"
                                            role="tab" aria-controls="support" aria-selected="true">Support Chat</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/">Home</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link" id="feedback-tab" data-bs-toggle="tab"
                                            href="#feedback" role="tab" aria-controls="feedback"
                                            aria-selected="false">Order Feedback</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php $profile = App\Models\User::where('id', auth()->user()->id)->get();?>
                        <div class="col-md-9">
                            <div class="tab-content account dashboard-content">

                            @include('user/layout/dashboard')
                                
                                <!-- order section -->
                                @include('user/layout/profile')
                             
                                <!-- return request -->
                                <!--  @include('user/layout/return') -->
                                <!-- wishlist -->
                                @include('user/layout/wishlist')
                                <!-- wallet -->
                             
                                @include('user/layout/wallet')
                                <!-- Safeer plus points -->
                                @include('user/layout/safeer-plus-point')
                                
                                <!-- track order -->
                                @include('user/layout/track-orders')
                                

                                <!-- address tab -->
                                @include('user/layout/address')
                               

                                <!-- account detail -->
                                @include('user/layout/account-detail')
                              
                                <!-- Support Ticket -->
                                @include('user/layout/support')

                                @include('user/layout/feedback')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
window.onload = (event) => {
  var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('tab')){
        if(urlParams.get('tab') == "wishlist"){
            // $(document).find('#wishlist-tab').trigger('click');
            var someTabTriggerEl = document.querySelector('#wishlist-tab')
            var tab = new bootstrap.Tab(someTabTriggerEl)
            tab.show()
        }
        if(urlParams.get('tab') == "support_ticket"){
            var someTabTriggerEl = document.querySelector('#support-tab')
            var tab = new bootstrap.Tab(someTabTriggerEl)
            tab.show()
        }
        if(urlParams.get('tab') == "profile"){
            var someTabTriggerEl = document.querySelector('#profile-tab')
            var tab = new bootstrap.Tab(someTabTriggerEl)
            tab.show()
        }
        if(urlParams.get('tab') == "track_your_order"){
            var someTabTriggerEl = document.querySelector('#track-orders-tab')
            var tab = new bootstrap.Tab(someTabTriggerEl)
            tab.show()
        }
        if(urlParams.get('tab') == "order_feedback"){
            var someTabTriggerEl = document.querySelector('#feedback-tab')
            var tab = new bootstrap.Tab(someTabTriggerEl)
            tab.show()
        }
    }
};
    
</script>

@endsection
