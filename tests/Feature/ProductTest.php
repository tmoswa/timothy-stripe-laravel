<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductTest extends TestCase
{

    /**
     * Testing Product.
     */
    public function test_product_creat(): void
    {
        $this->assertDatabaseHas('products', [
            'name'=>$this->product()[0]->name
        ]);
    }
    public function test_product_update(): void
    {
        $this->product()[0]->update(['name'=>"new name"]);
        $this->assertDatabaseHas('products', [
            'name'=>"new name"
        ]);
    }

    public function product():Collection
    {
        return Product::factory(1)
            ->has(Image::factory(1)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();
    }
}
