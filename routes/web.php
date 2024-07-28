<?php

use App\Livewire\Cart;
use App\Livewire\CheckoutStatus;
use App\Livewire\ProductDetail;
use App\Livewire\Welcome;
use App\Livewire\ViewOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('home');

Route::get('/product/{productId}', ProductDetail::class)->name('product');

Route::get('/checkout-status', CheckoutStatus::class)->name('checkout-status');

Route::get('/email-preview', function(){
    $order=\App\Models\Order::first();
   return new \App\Mail\OrderConfirmation($order);
})->name('email-preview');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Livewire\ProductDashBoard::class)->name('dashboard');
});


