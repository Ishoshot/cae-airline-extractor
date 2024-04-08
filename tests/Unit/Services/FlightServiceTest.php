<?php

namespace Tests\Unit\Services;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use App\Services\FlightService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $flightService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->flightService = new FlightService();
    }

    public function test_get_flights_for_next_week()
    {
        $currentDate = Carbon::create(2022, 1, 10); // Monday, January 10, 2022

        $expectedFlights = Event::factory()->count(3)->create([
            'type' => EventTypeEnum::Flight->value,
            'departure' => $currentDate->copy()->addDays(2)->toDateTimeString(),
        ]);

        $flights = $this->flightService->getFlightsForNextWeek($currentDate);

        $this->assertCount($expectedFlights->count(), $flights);
        foreach ($expectedFlights as $expectedFlight) {
            $this->assertContains($expectedFlight->id, $flights->pluck('id'));
        }
    }


    public function test_get_flights_starting_from_a_location()
    {
        $location = 'ARP';

        Event::factory()->count(7)->create();
        $expectedFlights = Event::factory()->count(2)->create(['from' => $location]);

        $flights = $this->flightService->getFlightsFromLocation($location);

        $this->assertCount($expectedFlights->count(), $flights);
        foreach ($expectedFlights as $expectedFlight) {
            $this->assertContains($expectedFlight->id, $flights->pluck('id'));
        }
    }
}
