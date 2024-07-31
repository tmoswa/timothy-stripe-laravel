<?php

namespace App\Livewire;

use Livewire\Component;

use App\Factories\CartFactory;

class CartDetail extends Component
{
    public $listeners=[
        'productAddedToCart'=> '$refresh',
        'productRemovedFromCart'=> '$refresh',
    ];
    public function getCountProperty()
    {
        return CartFactory::make()->items()->sum('quantity');
    }
    public function render()
    {
        return view('livewire.cart-detail');
    }
}
