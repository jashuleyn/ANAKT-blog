<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'approved', 'rejected']);
        
        return [
            'title' => fake()->sentence(rand(4, 8)),
            'content' => fake()->paragraphs(rand(3, 6), true),
            'user_id' => User::factory(),
            'status' => $status,
            'approved_at' => $status === 'approved' ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'created_at' => fake()->dateTimeBetween('-60 days', 'now'),
            'updated_at' => fake()->dateTimeBetween('-60 days', 'now'),
        ];
    }

    /**
     * Indicate that the post is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the post is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the post is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_at' => null,
        ]);
    }
}