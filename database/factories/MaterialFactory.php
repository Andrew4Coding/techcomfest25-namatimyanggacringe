<?php

namespace Database\Factories;

use App\Enums\MaterialType;
use App\Models\CourseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'id' => CourseItem::factory(),
            'file_url' => $this->faker->url(),
            'type' => $this->faker->randomElement(MaterialType::class)
        ];
    }
}
