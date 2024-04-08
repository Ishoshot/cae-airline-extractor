<?php

namespace App\Services;

use App\Enums\EventTypeEnum;
use App\Models\Event;
use Carbon\Carbon;

class FlightService
{

    /**
     * Get all FLT Events for the next week
     * @param \Carbon\Carbon $currentDate
     * @return mixed
     */
    public function getFlightsForNextWeek(Carbon $currentDate)
    {
        $startDate = $currentDate->copy()->startOfWeek()->addDays(1); // Start from Monday
        $endDate = $currentDate->copy()->endOfWeek()->addDays(1); // // End on Sunday

        return Event::whereType(EventTypeEnum::Flight->value)->whereBetween('departure', [$startDate, $endDate])->get();
    }


    /**
     * Get all FLT Events starting from a location
     * @param string $location
     * @return mixed
     */
    public function getFlightsFromLocation(string $location)
    {
        return Event::whereFrom($location)->get();
    }
}
