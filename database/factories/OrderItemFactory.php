<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'  => $this->faker->randomDigit(),
            'product_id'  => $this->faker->randomDigit(),
            'name'  => $this->faker->name(),
            'description'  => $this->faker->paragraph(1),
            'price'  => $this->faker->randomDigit(),
            'quantity'  => $this->faker->randomDigit(),
            'amount_discount'  => $this->faker->randomDigit(),
            'amount_subtotal'  => $this->faker->randomDigit(),

            'amount_tax'  => $this->faker->randomDigit(),
            'amount_total'  => $this->faker->randomDigit(),


        ];

    }
}
