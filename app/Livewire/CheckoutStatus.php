<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Livewire\Component;

class CheckoutStatus extends Component
{
    public $sessionId;
    public function mount()
    {
        $this->sessionId=request()->get('session_id');
    }
    public function render()
    {
        return view('livewire.checkout-status');
    }

    public function getOrderProperty()
    {
        return Order::where('stripe_checkout_session_id',$this->sessionId)->first();
    }
}
