<?php


namespace App\Actions;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Support\Str;

class InsertEventsAction
{

    /**
     * Handle Bulk Insert to Event model
     * @param array $events
     * @return void
     */
    public function execute(array $events)
    {
        logger(count($events));

        $eventsWithTimestamps = collect($events)->map(function ($event) {
            $now = Carbon::now()->toDateTimeString();
            $event['meta'] = json_encode($event['meta']);
            return array_merge($event, [
                'id' => Str::uuid(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        logger(count($eventsWithTimestamps));

        Event::insert($eventsWithTimestamps->toArray());
    }
}
