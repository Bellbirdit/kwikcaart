@extends('user/layout/master')
@section('title')
Kwikcaart | Wallet
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Wallet</h2>
        </div>
    </div>
    <div class="card1">

        <div class="card-body">
            <div class="row">

                <div class="col-md-12 text-center">
                    <div class="balance-wallet p-5  border">
                        <h3>Wallet Balance</h3>
                        <h1>AED : {{App\Models\Wallet::where('user_id',auth()->user()->id)->pluck('amount')->first();}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- card end// -->
</section>

@endsection
