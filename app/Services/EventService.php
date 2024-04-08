<?php

namespace App\Services;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use Carbon\Carbon;

class EventService
{

    /**
     * Gets all Events that occurs between 2 dates
     * @param string $startDate
     * @param string $endDate
     * @return Event[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getEventsBetweenDates(string $startDate, string $endDate)
    {
        return Event::whereBetween('departure', [$startDate, $endDate])
            ->orWhereBetween('arrival', [$startDate, $endDate])
            ->get();
    }


    /**
     * Get all SBY Events for the next week
     * @param \Carbon\Carbon $currentDate
     * @return mixed
     */
    public function getStandByEventsForNextWeek(Carbon $currentDate)
    {
        $startDate = $currentDate->copy()->startOfWeek()->addDays(1); // Start from Monday
        $endDate = $currentDate->copy()->endOfWeek()->addDays(1); // // End on Sunday

        return
            Event::whereType(EventTypeEnum::StandBy->value)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('departure', [$startDate, $endDate])
                    ->orWhereBetween('arrival', [$startDate, $endDate]);
            })
            ->get();
    }
}
