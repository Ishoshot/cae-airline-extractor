<?php

namespace App\Http\Controllers;

use Facades\App\Actions\InsertEventsAction;
use App\Factories\ParserFactory;
use App\Http\Requests\RosterUploadRequest;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;

class RosterUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RosterUploadRequest $request)
    {
        try {

            $file = $request->file('file');

            $parser = ParserFactory::make($file->extension());

            $htmlString = $parser->read($file->path());

            $events = $parser->parse($htmlString);

            InsertEventsAction::execute($events); //Using real time facade

            return response()->json(['message' => 'Roster uploaded successfully.'], 200);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
