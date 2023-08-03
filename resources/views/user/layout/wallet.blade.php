<div class="tab-pane fade" id="wallet" role="tabpanel" aria-labelledby="wallet-tab">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Wallet</h3>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 text-center">
                    <div class="balance-wallet p-5  border">
                        <h3>Wallet Balance</h3>
                        <h1>AED :
                            {{ App\Models\Wallet::where('user_id',auth()->user()->id)->pluck('amount')->first(); }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
