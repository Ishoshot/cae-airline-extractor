<?php

namespace Tests\Feature\Events;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListFlightsTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_list_all_flights_for_next_week()
    {
        $currentDate = Carbon::createFromFormat('Y-m-d', '2022-01-10');

        Event::factory()->count(9)->create([
            'type' => EventTypeEnum::Flight->value,
            'departure' => $currentDate->copy()->addDays(3)->toDateTimeString(),
        ]);

        $response = $this->getJson(route('flight.list', ['current_date' => $currentDate]));

        $response->assertStatus(200)
            ->assertJsonStructure(['flights']);

        $this->assertCount(9, $response['flights']);
    }



    public function test_user_can_list_all_flights_starting_from_krp_location()
    {
        $location = 'KRP';

        Event::factory()->count(6)->create([
            'from' => $location,
        ]);
        Event::factory()->count(3)->create(); //Mix with other events

        $response = $this->getJson(route('flight.location.list', ['location' => $location]));

        $response->assertStatus(200)
            ->assertJsonStructure(['flights']);

        $this->assertCount(6, $response['flights']);
    }


    public function test_user_can_list_all_flights_starting_from_cph_location()
    {
        $location = 'CPH';

        Event::factory()->count(3)->create([
            'from' => $location,
        ]);
        Event::factory()->count(2)->create(); //Mix with other events

        $response = $this->getJson(route('flight.location.list', ['location' => $location]));

        $response->assertStatus(200)
            ->assertJsonStructure(['flights']);

        $this->assertCount(3, $response['flights']);
    }
}
