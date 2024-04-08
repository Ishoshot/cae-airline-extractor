<?php

namespace Tests\Unit\Actions;

use App\Actions\InsertEventsAction;
use App\Enums\EventTypeEnum;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsertEventsActionTest extends TestCase
{
    use RefreshDatabase;

    protected $insertEventsAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->insertEventsAction = new InsertEventsAction();
    }

    /**
     * Helper function to build raw event data for insertion.
     *
     * @param array $overrides
     * @return array
     */
    private function buildEventData(array $overrides = []): array
    {
        return array_merge([
            'type' => 'some_type',
            'from' => '2022-01-15',
            'to' => '2022-01-16',
            'departure' => '2022-01-15 10:00:00',
            'arrival' => '2022-01-16 15:00:00',
            'meta' => ['key' => 'value'],
        ], $overrides);
    }

    public function test_it_inserts_multiple_events_into_the_database()
    {
        $events = [
            $this->buildEventData(['type' => EventTypeEnum::CheckIn->value]),
            $this->buildEventData(['type' => EventTypeEnum::CheckOut->value]),
            $this->buildEventData(['type' => EventTypeEnum::Flight->value]),
        ];

        $this->insertEventsAction->execute($events);

        $this->assertCount(count($events), Event::all());
        foreach ($events as $event) {
            $this->assertDatabaseHas('events', [
                'type' => $event['type'],
                'from' => $event['from'],
                'to' => $event['to'],
                'departure' => $event['departure'],
                'arrival' => $event['arrival'],
                'meta' => json_encode($event['meta']),
            ]);
        }
    }
}
