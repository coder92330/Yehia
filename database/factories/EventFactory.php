<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'agent_id'    => 1,
            'city_id'     => 1,
            'name'        => ['en' => $this->faker->name(), 'ar' => $this->faker->name()],
            'description' => ['en' => $this->faker->paragraph(3), 'ar' => $this->faker->paragraph(3)],
            'lat'         => $this->faker->latitude(),
            'lng'         => $this->faker->longitude(),
            'start_at'    => $this->faker->dateTimeBetween('now', '+1 year'),
            'end_at'      => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
