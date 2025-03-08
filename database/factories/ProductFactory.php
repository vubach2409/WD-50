<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3), // A short sentence for the name
            'description' => fake()->paragraph, // A paragraph for the description
            'price' => fake()->numberBetween(10, 1000), // Random price between 10 and 1000
            'image' => fake()->imageUrl(640, 480, 'products', true), // Random image URL from placehold.it
            'stock' => fake()->numberBetween(0, 100), // Random stock quantity between 0 and 100
            'product_detail' => fake()->paragraph, // A paragraph for the product detail
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory()
        ];
    }
}
