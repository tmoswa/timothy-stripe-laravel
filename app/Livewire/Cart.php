<?php

namespace App\Livewire;

use App\Actions\ShopActions\CreateStripeCheckoutSession;
use App\Factories\CartFactory;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Cart extends Component
{
    public $isModalOpen = 0;

    public $name;
    public $email;

    public $cardNumber, $expMonth, $expYear, $cvc;

    public function getCartProperty()
    {
        return CartFactory::make()->loadMissing(['items', 'items.product']);
    }

    public function getItemsProperty()
    {
        return $this->cart->items;
    }

    public function increment($itemId)
    {
        return $this->cart->items()->find($itemId)->increment('quantity');
    }

    public function decrement($itemId)
    {
        $item = $this->cart->items()->find($itemId);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        }

    }

    public function delete($itemId)
    {
        $this->cart->items()->where('id', $itemId)->delete();
        $this->dispatch('productRemovedFromCart');
    }

    public function render()
    {
        return view('livewire.cart.cart');
    }

    public function buy(){
        $this->name = '';
        $this->email = '';
        $this->isModalOpen = true;
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
        $this->cart['customer_id']=$customer->id;
        $this->cart->save();
        return $checkoutSession->createFromCart($this->cart);
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

}
