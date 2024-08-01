<?php

namespace App\Actions\ShopActions;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use Stripe\LineItem;

class HandleCheckoutSessionCompleted
{
    /**
     * 1. Receive Cashier's Stripe session and use it to link customer and cart and cartItems
     * 2. Check to avoid duplicates and proceed
     * 3. Create main Order followed by order Items
     */
    public function handle($sessionId)
    {
        $result=DB::transaction(function () use ($sessionId) {
            $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);
            $orderSaved = Order::Where('stripe_checkout_session_id', '!=', $session->id)->count();
            if ($orderSaved == 0) ;
            {
                $customer = Customer::find($session->metadata->customer_id);
                $amountTotal = $session->amount_total;
                $order = $customer->orders()->updateOrCreate(['stripe_checkout_session_id' => $session->id], [
                    'stripe_checkout_session_id' => $session->id,
                    'amount_shipping' => $session->total_details->amount_shipping,
                    'amount_discount' => $session->total_details->amount_discount,
                    'amount_tax' => $session->total_details->amount_tax,
                    'amount_subtotal' => $session->amount_subtotal,
                    'amount_total' => ($session->metadata->isDeposit == 'Yes') ? ($amountTotal * 2) : $amountTotal,
                    'amount_paid' => $session->amount_total,
                ]);
                $lineItems = Cashier::stripe()->checkout->sessions->allLineItems($session->id);

                $orderItems = collect($lineItems->all())->map(function (LineItem $line) use ($order) {
                    $product = Cashier::stripe()->products->retrieve($line->price->product);
                    return new OrderItem(
                        [
                            'product_id' => $product->metadata->product_id,
                            'order_id' => $order->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'price' => $line->price->unit_amount,
                            'quantity' => $line->quantity,
                            'amount_discount' => $line->amount_discount,
                            'amount_subtotal' => $line->amount_subtotal,
                            'amount_tax' => $line->amount_tax,
                            'amount_total' => $line->amount_total,
                        ]);

                });
                $order->items()->saveMany($orderItems);

                if($session->metadata->cart_id!=0){
                    $cart = Cart::find($session->metadata->cart_id);
                    $cart->items()->delete();
                    $cart->delete();
                }

            }
        });
        // Sending email
            $order = Order::where('stripe_checkout_session_id', $sessionId)->first();
            if ($order) {
                Mail::to($order->customer)->send(new OrderConfirmation($order));
            }

    }

}
