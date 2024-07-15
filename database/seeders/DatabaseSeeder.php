<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(8)
            ->has(Image::factory(3)->sequence(fn(Sequence $sequence)=>['featured'=>$sequence->index===0]))
            ->create();

        User::factory()->create([
            'email'=>'myproject22tim@gmail.com'
        ]);

    }
}
