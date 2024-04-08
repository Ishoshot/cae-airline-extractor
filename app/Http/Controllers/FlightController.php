<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flights\FlightsForNextWeekRequest;
use App\Services\FlightService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class FlightController extends Controller
{

    public function __construct(
        protected FlightService $flightService
    ) {
    }


    /**
     * Returns all Flights for next week
     * @param \App\Http\Requests\Flights\FlightsForNextWeekRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(FlightsForNextWeekRequest $request)
    {
        try {
            $payload = $request->validated();

            $currentDate = $request->filled('current_date') ? Carbon::createFromFormat('Y-m-d', $payload['current_date']) : Carbon::createFromFormat('Y-m-d', '2022-01-14');

            $flights = $this->flightService->getFlightsForNextWeek($currentDate);

            return response()->json(['flights' => $flights], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * Get all flights starting from a particular location
     * @param \Illuminate\Http\Request $request
     * @param mixed $location
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listFlightsFromLocation(Request $request, $location)
    {
        try {

            $flights = $this->flightService->getFlightsFromLocation($location);

            return response()->json(['flights' => $flights], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
