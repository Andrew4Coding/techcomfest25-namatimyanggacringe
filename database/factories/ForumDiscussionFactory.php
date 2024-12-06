<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumDiscussion>
 */
class ForumDiscussionFactory extends Factory
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
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'forum_id' => Forum::factory(),
            'creator_id' => User::factory(),
        ];
    }
}
