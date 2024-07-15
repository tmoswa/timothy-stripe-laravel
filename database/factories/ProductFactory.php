<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'   =>   $this->faker->unique()->randomElement(['Honda CBR1000RR-R','Elvis Edition','New Scout','Suzuki Model','Honda Grom','Supersport 300','2024 Verge TS','2025 BMW','Motorcycle Handlebars','MV Agusta','Ducati DesertX Discovery','PACKTALK PRO']),
            'description'    =>   $this->faker->paragraph(2),
            'price'  =>  ($this->faker->numberBetween(20_00,80_00)*100)
        ];
    }
}
