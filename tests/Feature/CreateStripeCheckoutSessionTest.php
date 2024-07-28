<?php

namespace Tests\Feature;

use App\Actions\StripeActions\CreateStripeCheckoutSession;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CreateStripeCheckoutSessionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    private $process;

    protected function setUp(): void
    {
        parent::setUp();
        $this->process = new CreateStripeCheckoutSession();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test creating a Stripe checkout session on full payment.
     */
    public function test_create_stripe_checkout_session(): void
    {
        $customer=Customer::factory(1)->create();
        $product= Product::factory(1)
            ->has(Image::factory(1)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();
        $response =$this->process->guestClientPurchase($customer[0],$product[0],100,false);
        $this->assertEquals($response->status,"open");
    }

    /**
     * Test creating a Stripe checkout session with invalid customer on full payment.
     */
    public function test_create_stripe_checkout_session_invalid_customer(): void
    {
        // Arrange
        $product = Product::factory()
            ->has(Image::factory()->sequence(fn (Sequence $sequence) => ['featured' => $sequence->index === 0]))
            ->create();

        // Act and Assert
        $this->expectException(\TypeError::class);
        $this->process->guestClientPurchase(null, $product, 100, false);
    }

    /**
     * Test creating a Stripe checkout session with invalid product on full payment.
     */
    public function test_create_stripe_checkout_session_invalid_product(): void
    {
        // Arrange
        $customer = Customer::factory()->create();

        // Act and Assert
        $this->expectException(\TypeError::class);
        $this->process->guestClientPurchase($customer, null, 100, false);
    }

    /**
     * Test creating a Stripe checkout session with invalid amount on full payment.
     */
    public function test_create_stripe_checkout_session_invalid_amount(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $product = Product::factory()
            ->has(Image::factory()->sequence(fn (Sequence $sequence) => ['featured' => $sequence->index === 0]))
            ->create();

        // Act and Assert
        $this->expectException(\Exception::class);
        $this->process->guestClientPurchase($customer, $product, -1, false);
    }



    /**
     * Test creating a Stripe checkout session on deposit.
     */
    public function test_create_stripe_checkout_session_on_deposit(): void
    {
        $customer=Customer::factory(1)->create();
        $product= Product::factory(1)
            ->has(Image::factory(1)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();
        $response =$this->process->guestClientPurchase($customer[0],$product[0],100,true);
        $this->assertEquals($response->status,"open");
    }

    /**
     * Test creating a Stripe checkout session with invalid customer on deposit.
     */
    public function test_create_stripe_checkout_session_invalid_customer_on_deposit(): void
    {
        // Arrange
        $product = Product::factory()
            ->has(Image::factory()->sequence(fn (Sequence $sequence) => ['featured' => $sequence->index === 0]))
            ->create();

        // Act and Assert
        $this->expectException(\TypeError::class);
        $this->process->guestClientPurchase(null, $product, 100, true);
    }

    /**
     * Test creating a Stripe checkout session with invalid product on deposit.
     */
    public function test_create_stripe_checkout_session_invalid_product_on_deposit(): void
    {
        // Arrange
        $customer = Customer::factory()->create();

        // Act and Assert
        $this->expectException(\TypeError::class);
        $this->process->guestClientPurchase($customer, null, 100, true);
    }

    /**
     * Test creating a Stripe checkout session with invalid amount on deposit.
     */
    public function test_create_stripe_checkout_session_invalid_amount_on_deposit(): void
    {
        // Arrange
        $customer = Customer::factory()->create();
        $product = Product::factory()
            ->has(Image::factory()->sequence(fn (Sequence $sequence) => ['featured' => $sequence->index === 0]))
            ->create();

        // Act and Assert
        $this->expectException(\Exception::class);
        $this->process->guestClientPurchase($customer, $product, -1, true);
    }
}
