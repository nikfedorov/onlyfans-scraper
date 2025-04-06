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
            'username' => $this->faker->userName(),
        ];
    }

    /**
     * Model that has all data.
     */
    public function withAllData(): self
    {
        return $this->state([
            'name' => $this->faker->name(),
            'likes' => $this->faker->numberBetween(0, 10000),
            'bio' => $this->faker->text(100),
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
}
