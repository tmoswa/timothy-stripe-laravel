<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_customer(): void
    {
        $this->assertDatabaseHas('customers', [
            'name'=>$this->customer()[0]->name
        ]);
    }

    public function customer():Collection
    {
        return Customer::factory(1)->create();

    }
}
