<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'alt_text' => $this->faker->sentence,
            'seo_meta_title' => $this->faker->sentence(3),
            'og_meta_title' => $this->faker->sentence(3),
            'seo_meta_description' => $this->faker->sentence(12),
            'og_meta_description' => $this->faker->sentence(8),
            'seo_meta_keywords' => 'shoes, fashion, online',
            'og_meta_keywords' => 'shoes, fashion, online',
            'seo_canonical' => null,
            'meta_robots' => 'index,follow',
        ];
    }
}
