<?php

namespace App\Actions\StripeActions;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use Stripe\LineItem;

class HandleCheckoutSessionCompleted
{
    /**
     * 1. Receive Cashier's Stripe session and use it to link customer
     * 2. Check for duplicates and proceed
     * 3. Create main Order followed by order Items
     * 4. This method loops through all order items and save them to the database
     * 5. The use of firstOrCreate is to avoid duplicates
     * ToDo
     * Make the itemsSave method to saveMany()
     */
    public function handle($sessionId,bool $testMode=false)
    {
        DB::transaction(function () use ($sessionId) {
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

                collect($lineItems->all())->map(function (LineItem $line) use ($order) {
                    $product = Cashier::stripe()->products->retrieve($line->price->product);
                    return $order->items()->firstOrCreate(
                        [
                            'product_id' => $product->metadata->product_id,
                            'order_id' => $order->id
                        ],
                        [
                            'product_id' => $product->metadata->product_id,
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
                Mail::to($customer)->send(new OrderConfirmation($order));
            }
        });
        return ($testMode)?"success" :"";
    }

}
