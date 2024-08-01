<?php

namespace App\Actions\ShopActions;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Money\Money;
use Illuminate\Database\Eloquent\Collection;

class CreateStripeCheckoutSession
{
    /**
     * Cashier guide to check out,
     * Attach check out to local customer object and create metadata to use when object returns
     */
    public function guestClientPurchase(Customer $customer, Product $product, $quantity, $isDeposit = false)
    {
        return $customer
            ->checkout(
                [$this->formatProduct($product, $quantity, $isDeposit)],
                [
                    'payment_intent_data' => ['setup_future_usage' => 'off_session'],
                    'success_url' => route('checkout-status') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('product', $product->id),
                    'metadata' => [
                        'customer_id' => $customer->id,
                        'isDeposit' => $isDeposit ? 'Yes' : 'No',
                    ],

                ]
            );
    }

    /**
     * Return the formatted product accepted to stripe API, check documentation on price_data
     * https://docs.stripe.com/api/products
     *
     * Determine amount based on deposit or full payment
     * ToDo
     * Optimize $unitAmount statement
     */
    private function formatProduct(Product $product, $quantity, bool $isDeposit)
    {
        $unitAmount = $isDeposit ?
            (Money::USD((integer)(($product->price->getAmount() / 2)))->getAmount()) :
            $product->price->getAmount();

        return [
            'price_data' => [
                'currency' => 'USD',
                'unit_amount' => $unitAmount,
                'product_data' => [
                    'name' => $product->name,
                    'description' => $product->description,
                    'metadata' => [
                        'product_id' => $product->id,
                        'cart_id'=>0
                    ]
                ]
            ],
            'quantity' => $quantity,
        ];
    }






    public function createFromCart(Cart $cart)
    {
        return $cart->customer
            ->allowPromotionCodes()
            ->checkout(
                $this->formatCartItems($cart->items),
                [
                    'customer_update'=>[
                        'shipping' => 'auto'
                    ],
                    'shipping_address_collection'=>[
                        'allowed_countries'=>[
                            'US','NL','ZW','SA'
                        ]
                    ],
                    'success_url'=>route('checkout-status').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url'=>route('cart'),
                    'metadata'=>[
                        'customer_id'=>$cart->customer->id,
                        'cart_id'=>$cart->id,
                    ]
                ]
            );
    }

    private function formatCartItems(Collection $items)
    {
        return $items->loadMissing('product')->map(function (CartItem $item){
            return [
                'price_data' => [
                    'currency' => 'USD',
                    'unit_amount' => $item->product->price->getAmount(),
                    'product_data' => [
                        'name' => $item->product->name,
                        'description' => $item->product->description,
                        'metadata' => [
                            'product_id' => $item->product->id,
                        ]
                    ]
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();
    }


}
