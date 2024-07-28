<?php

namespace Tests\Feature;

use App\Actions\StripeActions\CreateStripeCheckoutSession;
use App\Actions\StripeActions\HandleCheckoutSessionCompleted;
use App\Listeners\StripePaymentEventListener;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;
use Money\Money;
use Tests\TestCase;

class StripePaymentEventListenerTest extends TestCase
{
    use RefreshDatabase;
    public function test_stripe_payment_event_listener()
    {
        // Arrange
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();
        $checkoutSession = (new CreateStripeCheckoutSession())->guestClientPurchase($customer, $product, 1);
        $event = new WebhookReceived(['type' => 'checkout.session.completed', 'data' => ['object' => ['id' => $checkoutSession->id]]]);

        // Act
        (new StripePaymentEventListener())->handle($event);

        // Assert
        $this->assertDatabaseHas('orders', ['stripe_checkout_session_id' => $checkoutSession->id]);
    }
}

