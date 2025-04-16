<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure there are categories and brands (create some if they don't exist)
        if (Category::count() == 0) {
            Category::create(['name' => 'Electronics']);
            Category::create(['name' => 'Clothing']);
            Category::create(['name' => 'Books']);
        }
        if (Brand::count() == 0) {
            Brand::create(['name' => 'Brand A']);
            Brand::create(['name' => 'Brand B']);
            Brand::create(['name' => 'Brand C']);
        }

        $categoryIds = Category::pluck('id')->toArray(); // Get all category IDs
        $brandIds = Brand::pluck('id')->toArray(); // Get all brand IDs

        Product::factory()->count(20)->create();
    }
}

