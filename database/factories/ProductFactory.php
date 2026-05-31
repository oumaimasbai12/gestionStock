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
        $categories = ['Liants Hydrauliques', 'Acier & Ferraillage', 'Granulats & Sables', 'Maçonnerie & Blocs', 'Peintures & Enduits', 'Électricité', 'Outillage'];
        return [
            'name' => $this->faker->words(3, true),
            'category' => $this->faker->randomElement($categories),
            'purchase_price' => $this->faker->randomFloat(2, 5, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
