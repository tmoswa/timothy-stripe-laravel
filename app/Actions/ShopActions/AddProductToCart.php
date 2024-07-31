<?php

namespace App\Actions\ShopActions;

use App\Factories\CartFactory;
use App\Models\Cart;
use App\Models\Product;

class AddProductToCart
{
    public function add(Product $product,$quantity = 1, $cart = null)
    {
        $item = ($cart ?: CartFactory::make())->items()->firstOrCreate(
            [
                'product_id' => $product->id,
            ],
            [
                'quantity' => 0,
            ],
        );
        $item->increment('quantity', $quantity);
        $item->touch();
    }

}
