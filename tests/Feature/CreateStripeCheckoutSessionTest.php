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
    public function test_create_stripe_checkout_session(): void
    {
        $customer=Customer::factory(1)->create();
        $product= Product::factory(1)
            ->has(Image::factory(1)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();
        $response =$this->process->guestClientPurchase($customer[0],$product[0],100,false);
        $this->assertEquals($response->status,"open");
    }
}
