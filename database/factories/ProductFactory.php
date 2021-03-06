<?php

declare(strict_types=1);

namespace Tipoff\Products\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Products\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $sentence = $this->faker->unique()->sentence;
        return [
            'sku'           => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{3}'),
            'slug'          => Str::slug($sentence),
            'title'         => $sentence,
            'amount'        => $this->faker->numberBetween(100, 10000),
            'location_id'   => $this->faker->optional()->passthrough(
                randomOrCreate(app('location'))
            ),
            'creator_id'    => randomOrCreate(app('user')),
        ];
    }
}
