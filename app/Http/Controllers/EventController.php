<?php

namespace App\Http\Controllers;

use App\Http\Requests\Events\EventsBetweenDatesRequest;
use App\Http\Requests\Events\StandByEventsForNextWeekRequest;
use App\Services\EventService;
use Carbon\Carbon;
use Exception;

class EventController extends Controller
{

    public function __construct(
        protected EventService $eventService
    ) {
    }


    /**
     * Returns all Events between 2 dates
     * @param \App\Http\Requests\Events\EventsBetweenDatesRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(EventsBetweenDatesRequest $request)
    {
        try {
            $payload = $request->validated();

            $startDate = $payload['start_date'];
            $endDate = $payload['end_date'];

            $events = $this->eventService->getEventsBetweenDates($startDate, $endDate);

            return response()->json(['events' => $events], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    /**
     * Returns all SBY Events for next week
     * @param \App\Http\Requests\Events\StandByEventsForNextWeekRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listStandbyEvents(StandByEventsForNextWeekRequest $request)
    {
        try {
            $payload = $request->validated();

            $currentDate = $request->filled('current_date') ? Carbon::createFromFormat('Y-m-d', $payload['current_date']) : Carbon::createFromFormat('Y-m-d', '2022-01-14');

            $events = $this->eventService->getStandByEventsForNextWeek($currentDate);

            return response()->json(['events' => $events], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
