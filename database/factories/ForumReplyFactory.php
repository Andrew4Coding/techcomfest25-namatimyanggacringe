<?php

namespace Database\Factories;

use App\Models\ForumDiscussion;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumReply>
 */
class ForumReplyFactory extends Factory
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
            'forum_discussion_id' => ForumDiscussion::factory(),
            'sender_id' => User::factory(),
            'reply_to_id' => ForumReply::factory(),
            'content' => $this->faker->paragraph(),
            'upvote' => $this->faker->numberBetween(),
            'downvote' => $this->faker->numberBetween(),
        ];
    }
}
