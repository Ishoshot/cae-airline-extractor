<?php

namespace Tests\Unit\Services;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $eventService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventService = new EventService();
    }

    public function test_get_events_between_dates()
    {
        $startDate = '2022-01-10';
        $endDate = '2022-01-16';
        $expectedEvents = Event::factory()->count(2)->create([
            'departure' => '2022-01-12 12:00:00',
            'arrival' => '2022-01-13 12:00:00',
        ]);

        $events = $this->eventService->getEventsBetweenDates($startDate, $endDate);

        $this->assertCount($expectedEvents->count(), $events);
        foreach ($expectedEvents as $expectedEvent) {
            $this->assertContains($expectedEvent->id, $events->pluck('id'));
        }
    }

    public function test_get_stand_by_events_for_next_week()
    {
        $currentDate = Carbon::create(2022, 1, 10); // Monday, January 10, 2022

        $expectedStandByEvents = Event::factory()->count(3)->create([
            'type' => EventTypeEnum::StandBy->value,
            'departure' => $currentDate->copy()->addDays(3)->toDateTimeString(),
        ]);

        $standByEvents = $this->eventService->getStandByEventsForNextWeek($currentDate);

        $this->assertCount($expectedStandByEvents->count(), $standByEvents);
        foreach ($expectedStandByEvents as $expectedEvent) {
            $this->assertContains($expectedEvent->id, $standByEvents->pluck('id'));
        }
    }
}
