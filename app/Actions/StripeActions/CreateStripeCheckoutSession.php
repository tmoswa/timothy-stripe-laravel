<?php

namespace App\Actions\StripeActions;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Money\Money;

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
                    //'saved_payment_method_options' => ['payment_method_save' => 'enabled'],
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
                    ]
                ]
            ],
            'quantity' => $quantity,
        ];
    }

}
