<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'city_id'     => $this->faker->numberBetween(1, 7),
            'package_id'  => $this->faker->numberBetween(1, 3),
            'name'        => ['en' => 'EgyptNavigator', 'ar' => 'مصر نافيجيتور'],
            'email'       => $this->faker->unique()->safeEmail,
            'website'     => $this->faker->url,
            'address'     => $this->faker->address,
            'specialties' => $this->faker->paragraphs(3, true),
            'description' => $this->faker->paragraphs(3, true),
            'facebook'    => $this->faker->url,
            'twitter'     => $this->faker->url,
            'instagram'   => $this->faker->url,
            'linkedin'    => $this->faker->url,
        ];
    }
}
