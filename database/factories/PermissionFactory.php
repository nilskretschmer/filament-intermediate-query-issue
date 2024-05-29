<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->word(),
        ];
    }

    public function download(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'download',
            ];
        });
    }

    public function upload(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'upload',
            ];
        });
    }
}