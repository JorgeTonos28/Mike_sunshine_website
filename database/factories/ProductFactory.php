<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return ['title' => 'Obra de prueba', 'slug' => Str::uuid()->toString(), 'kind' => 'painting', 'price_minor' => 250000, 'currency' => 'DOP', 'stock' => 1, 'availability' => 'available', 'is_published' => true];
    }
}
