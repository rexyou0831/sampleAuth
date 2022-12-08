<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $type = [ 'horror', 'fiction', 'non_fiction', 'history', 'health', 'gym', 'society', 'economic' ];

        return [
            'name'=> $this->faker->name,
            'description'=> $this->faker->sentence,
            'type'=> $type[array_rand($type)],
            'publication_year'=> (string) $this->faker->year
        ];
    }
}
