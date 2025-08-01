<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(4),
            'e_issn' => $this->faker->numerify('####-####'),
            'p_issn' => $this->faker->numerify('####-####'),
            'chief_editor' => $this->faker->name() . ', Ph.D.',
            'website' => $this->faker->url(),
            'publisher_id' => Publisher::factory(),
        ];
    }
}
