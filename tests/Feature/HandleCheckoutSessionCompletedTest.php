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
use Tests\TestCase;

class HandleCheckoutSessionCompletedTest extends TestCase
{
    use RefreshDatabase;

    private $process;
    private $handling;

    protected function setUp(): void
    {
        parent::setUp();
        $this->process = new CreateStripeCheckoutSession();
        $this->handling=new HandleCheckoutSessionCompleted();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
    public function test_handle_checkout_completed(): void
    {
        $response=$this->test_create_stripe_checkout_session();
        $data=$this->handling->handle($response->id,true);
        $this->assertEquals($data,"success");
    }

    public function test_create_stripe_checkout_session()
    {
        $customer=Customer::factory(1)->create();
        $product= Product::factory(1)
            ->has(Image::factory(1)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();
        return $this->process->guestClientPurchase($customer[0],$product[0],100,false);

    }
}
