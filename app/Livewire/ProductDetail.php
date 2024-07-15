<?php

namespace App\Livewire;

use App\Actions\StripeActions\CreateStripeCheckoutSession;
use App\Models\Customer;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use \App\Actions\StripeActions\AddProductVariantToCart;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class ProductDetail extends Component
{
    use InteractsWithBanner;
    public $productId;
    public $quantity;
    public $isModalOpen = 0;

    public $name;
    public $email;
    public bool $isDeposit=false;

    public $rules=[
        'quantity'=>['required','integer'],
    ];

    public function getProductProperty()
    {
        return \App\Models\Product::findOrFail($this->productId);
    }
    public function render()
    {
        return view('livewire.make-purchase.product-details');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function buy(){
        $this->validate();
        $this->name = '';
        $this->email = '';
        $this->isModalOpen = true;
    }
    public function deposit(){
        $this->isDeposit=true;
        $this->buy();
    }
    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }
    public function saveData(CreateStripeCheckoutSession $checkoutSession){
        $this->validate([
            'name' => 'required|min:4',
            'email' => 'required|string|email|max:255,',
        ]);
        $customer=Customer::updateOrCreate(['email'=>$this->email],[
            'name'=>$this->name,
            'email'=>$this->email
        ]);
       return $checkoutSession->guestClientPurchase($customer,$this->product,$this->quantity,$this->isDeposit);
    }

    public function addToCart(AddProductVariantToCart $cart)
    {
        $cart->add(variantId:$this->quantity);
        $this -> dispatch('productAddedToCart');
    }
}
