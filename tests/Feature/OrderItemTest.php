<?php

namespace Tests\Feature;


use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_order_item_creation(): void
    {
        $this->assertDatabaseHas('order_items', [
            'name'=>$this->orderItem()[0]->name
        ]);
    }
    public function test_product_item_update(): void
    {
        $this->orderItem()[0]->update(['name'=>"123456789"]);
        $this->assertDatabaseHas('order_items', [
            'name'=>"123456789"
        ]);
    }
    public function orderItem():Collection
    {
        return OrderItem::factory(1)->create();
    }
}
