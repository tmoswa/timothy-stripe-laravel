<?php

namespace Tests\Feature;

use App\Actions\StripeActions\CreateStripeCheckoutSession;
use App\Actions\StripeActions\HandleCheckoutSessionCompleted;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Money\Money;
use Stripe\Checkout\Session;
use Tests\TestCase;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;

class HandleCheckoutSessionCompletedTest extends TestCase
{
    use RefreshDatabase;


    public function test_handle_checkout_session_completed()
    {
        // Arrange
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();
        $checkoutSession = (new CreateStripeCheckoutSession())->guestClientPurchase($customer, $product, 1);
        $session = Cashier::stripe()->checkout->sessions->retrieve($checkoutSession->id);

        // Act
        (new HandleCheckoutSessionCompleted())->handle($session->id);

        // Assert
        $order = Order::where('stripe_checkout_session_id', $session->id)->first();
        $this->assertNotNull($order);
        //dump($session);
        $this->assertEquals($session->amount_total, $order->amount_total->getAmount());
        $this->assertEquals($session->total_details->amount_shipping, $order->amount_shipping->getAmount());
        $this->assertEquals($session->total_details->amount_discount, $order->amount_discount->getAmount());
        $this->assertEquals($session->total_details->amount_tax, $order->amount_tax->getAmount());
        $this->assertEquals($session->amount_subtotal, $order->amount_subtotal->getAmount());
        $this->assertEquals($session->amount_total, $order->amount_paid->getAmount());

        // Check if email was sent
        Mail::fake();
        Mail::to($customer->email)->send(new OrderConfirmation($order));
        Mail::assertSent(OrderConfirmation::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;;
        });


        // Assert
        $this->assertDatabaseHas('orders', ['stripe_checkout_session_id' => $session->id]);
        $this->assertDatabaseHas('order_items', ['order_id' => Order::where('stripe_checkout_session_id', $session->id)->first()->id]);
    }

}
