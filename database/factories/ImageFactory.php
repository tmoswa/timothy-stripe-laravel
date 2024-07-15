<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path'=>$this->faker->unique()->randomElement([
                'media/example-1.png',
                'media/example-2.png',
                'media/example-3.png',
                'media/example-4.png',
                'media/example-5.png',
                'media/example-6.png',
                'media/example-7.png',
                'media/example-8.png',
                'media/example-9.png',
                'media/example-10.png',
                'media/example-11.png',
                'media/example-12.png',
                'media/example-13.png',
                'media/example-14.png',
                'media/example-15.png',
                'media/example-16.png',
                'media/example-17.png',
                'media/example-18.png',
                'media/example-19.png',
                'media/example-20.png',
                'media/example-21.png',
                'media/example-22.png',
                'media/example-23.png',
                'media/example-24.png',
                'media/example-15.png'
            ])
        ];
    }
}
