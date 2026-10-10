<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'last_name'   => fake()->lastName(),
            'first_name'  => fake()->firstName(),
            'gender'      => fake()->randomElement([1, 2, 3]),
            'email'       => fake()->unique()->safeEmail(),
            'tel'         => '090' . fake()->numerify('########'),
            'address'     => fake()->address(),
            'building'    => fake()->optional(0.5)->secondaryAddress(),
            'detail'      => fake()->realText(80),
        ];
    }
}