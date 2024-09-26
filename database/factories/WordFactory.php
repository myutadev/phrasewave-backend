<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Word>
 */
class WordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'language_code' => Language::factory()->create()->language_code,
            'word' => '滔滔不绝',
            'display_count' => 1,
            'is_favorite' => false,
            'created_at' => now(),
            'updated_at' => null,
            'deleted_at' => null,
        ];
    }
}
