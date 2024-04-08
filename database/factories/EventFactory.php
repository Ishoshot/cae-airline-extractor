<?php

namespace Database\Factories;

use App\Enums\EventTypeEnum;
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
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(EventTypeEnum::toArray()),
            'from' => $this->faker->address(),
            'to' => $this->faker->address(),
            'departure' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'arrival' => $this->faker->dateTimeBetween('+2 weeks', '+3 weeks'),
            'meta' => ['key' => 'value'],
        ];
    }
}
