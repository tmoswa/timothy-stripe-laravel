<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_order_creation(): void
    {
        $this->assertDatabaseHas('orders', [
            'customer_id'=>$this->customer()[0]->orders[0]->customer_id
        ]);
    }
    public function test_order_update(): void
    {
        $this->customer()[0]->orders[0]->update(['stripe_checkout_session_id'=>"123456789"]);
        $this->assertDatabaseHas('orders', [
            'stripe_checkout_session_id'=>"123456789"
        ]);
    }

    public function customer():Collection
    {
        $customer=Customer::factory(1);
        return $customer
            ->has(Order::factory(1))
                ->create();
    }
}
