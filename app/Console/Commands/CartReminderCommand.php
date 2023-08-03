<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\CartReminder;

class CartReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send cart reminder emails to users who have pending carts older than 7 days.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $carts = Cart::where('updated_at', '<=', $sevenDaysAgo)
                    ->where('status', 'pending')
                    ->where('email_forwarded', 0)
                    ->get();
    
        // Group the pending carts by user ID
        $cartsByUser = $carts->groupBy('user_id');
    
        foreach ($cartsByUser as $userId => $userCarts) {
            $user = User::find($userId);
            if ($user) {
                Mail::to($user)->send(new CartReminder($user));
                $userCarts->each(function ($cart) {
                    $cart->update(['email_forwarded' => 1]);
                });
            }
        }
    
        // Delete carts that haven't been updated for 15 days
        $fifteenDaysAgo = Carbon::now()->subDays(15);
        $oldCarts = Cart::where('created_at', '<=', $fifteenDaysAgo)
                    ->where('status', 'pending')
                    ->where('email_forwarded', 1)
                    ->get();
    
        $oldCarts->each(function ($cart) {
            $cart->delete();
        });
    }
}
