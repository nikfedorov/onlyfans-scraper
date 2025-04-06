<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName(),
        ];
    }

    /**
     * Model that has all data.
     */
    public function withAllData(): self
    {
        return $this->state([
            'name' => fake()->name(),
            'likes' => fake()->numberBetween(0, 10000),
            'bio' => fake()->text(100),
        ]);
    }

    /**
     * Model that has real text.
     */
    public function withRealText(): self
    {
        return $this->state([
            'bio' => fake()->realText(300),
        ]);
    }

    /**
     * Model with a specific username.
     */
    public function username(string $username): self
    {
        return $this->state([
            'username' => $username,
        ]);
    }

    /**
     * Model with a specific likes number.
     */
    public function likes(int $likes): self
    {
        return $this->state([
            'likes' => $likes,
        ]);
    }
}
