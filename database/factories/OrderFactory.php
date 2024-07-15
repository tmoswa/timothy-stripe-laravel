<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stripe_checkout_session_id' =>  $this->faker->randomElement(['session 1','session 2','session 3','session 4']),
            'amount_shipping'  => $this->faker->randomDigit(),
            'amount_discount'  => $this->faker->randomDigit(),
            'amount_tax'  => $this->faker->randomDigit(),
            'amount_subtotal'  => $this->faker->randomDigit(),
            'amount_total'  => $this->faker->randomDigit(),
            'amount_paid'  => $this->faker->randomDigit(),

        ];

    }
}
