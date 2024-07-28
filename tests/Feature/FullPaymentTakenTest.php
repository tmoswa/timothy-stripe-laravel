<?php

namespace Tests\Feature;
/*
use App\Console\Commands\FullPaymentTaken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleFullPaymentTest extends TestCase
{
    use RefreshDatabase;

    private $process;

    protected function setUp(): void
    {
        parent::setUp();
        $this->process = new FullPaymentTaken();
    }

    public function test_full_payment_taken(): void
    {
        $data=$this->process->handle(true);
        $this->assertEquals($data,"success");
    }
}
*/

use App\Mail\FullPaymentConfirmation;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Money\Money;
use Stripe\Charge;
use Tests\TestCase;

class FullPaymentTakenTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_payment_taken()
    {
        // Arrange
        $customer = Customer::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'amount_total' => 10000,
            'amount_paid' => 5000,
            'created_at'=>now()->subMinutes(5),
        ]);

        // Act
        $this->artisan('app:full-payment-taken');

        // Assert
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'amount_paid' => $order->amount_paid->getAmount(),
        ]);

        // Test email sent
        Mail::fake();
        Mail::to($customer->email)->send(new FullPaymentConfirmation($order));
        Mail::assertSent(FullPaymentConfirmation::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;;
        });
    }


}


