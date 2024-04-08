<?php

namespace Tests\Feature\Events;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_all_events_between_two_dates()
    {
        $startDate = '2022-01-01';
        $endDate = '2022-01-07';

        Event::factory()->count(10)->create([
            'departure' => $startDate . ' 12:00:00',
            'arrival' => $endDate . ' 12:00:00',
        ]);
        Event::factory()->count(8)->create(); //Mix with other events

        $response = $this->getJson(route('event.list', ['start_date' => $startDate, 'end_date' => $endDate]));

        $response->assertStatus(200)
            ->assertJsonStructure(['events']);

        $this->assertCount(10, $response['events']);
    }


    public function test_user_can_list_all_standby_events_for_next_week()
    {
        $currentDate = Carbon::createFromFormat('Y-m-d', '2022-01-10');

        Event::factory()->count(9)->create([
            'type' => EventTypeEnum::StandBy->value,
            'departure' => $currentDate->copy()->addDays(3)->toDateTimeString(),
        ]);

        $response = $this->getJson(route('event.standby.list', ['current_date' => $currentDate]));

        $response->assertStatus(200)
            ->assertJsonStructure(['events']);

        $this->assertCount(9, $response['events']);
    }
}
