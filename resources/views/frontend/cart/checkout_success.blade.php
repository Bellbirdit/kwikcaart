@extends('frontend/layout/master')
@section('title')
Safeer | Success
@endsection
@section('frontend/content')


<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Shop
                <span></span> Checkout Success
            </div>
        </div>
    </div>
    <div class="container mb-80 mt-50">
        <div class="row">
            <div class="col-lg-12 text-center mb-40">
                <h1 class="heading-2 mb-10">Thankyou {{Auth::user()->name}} for placing an order on Safeer Market</h1>
            </div>
        </div>
        <?php
      $userID = Auth::user()->id;

// Retrieve the order number of the most recent order for the user
$ordernumber = App\Models\Order::where('user_id', $userID)
    ->orderBy('created_at', 'desc') // Order by creation date in descending order to get the most recent order
    ->pluck('order_number') // Retrieve the order_number column value
    ->first(); // Retrieve the first result

?>
        <div class="row">
    <div class="col-lg-12 text-center">
        <h3 class="text-success">Your Order and Tracking Number is {{$ordernumber}}</h3>
        <a href="{{url('/user/dashboard')}}" class="btn btn-success mt-2">Check Your Orders</a>
    </div>
</div>
</main>
@endsection