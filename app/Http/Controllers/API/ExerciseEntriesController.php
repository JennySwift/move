<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Exercises\ExerciseEntryTransformer;
use App\Models\Entry;
use App\Models\Exercise;
use App\Models\Unit;
use App\Repositories\ExerciseEntriesRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class ExerciseEntriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseEntriesController extends Controller
{
    /**
     * @var ExerciseEntriesRepository
     */
    private $exerciseEntriesRepository;

    /**
     * @param ExerciseEntriesRepository $exerciseEntriesRepository
     */
    public function __construct(ExerciseEntriesRepository $exerciseEntriesRepository)
    {
        $this->exerciseEntriesRepository = $exerciseEntriesRepository;
    }

    /**
     * GET /api/exerciseEntries
     * Get the user's exercise entries for the day
     * @param Request $request
     * @param $date
     * @return Response
     */
    public function index(Request $request, $date)
    {
        $entries = Entry::forCurrentUser()
            ->where('date', $date)
            ->orderBy('id', 'asc')
            ->get();

        $entries = $this->exerciseEntriesRepository->compactExerciseEntries($entries, $date);

        $entries = $this->transform($this->createCollection($entries, new ExerciseEntryTransformer))['data'];
        return response($entries, Response::HTTP_OK);
    }

    /**
     * Returns all entries for an exercise on a specific date
     * where the exercise has the specified unit
     *
     * Get all entries for one exercise with a particular unit on a particular date.
     * Get exercise name, quantity, and entry id.
     * @param Request $request
     * @return array
     */
    public function getEntriesForSpecificExerciseAndDateAndUnit(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));
        $entries = Entry::where('exercise_id', $exercise->id)
            ->where('date', $request->get('date'))
            ->where('exercise_unit_id', $request->get('exercise_unit_id'))
            ->with('exercise')
            ->get();

        return $this->transform($this->createCollection($entries, new ExerciseEntryTransformer))['data'];
    }

    /**
     * POST /api/exerciseEntries
     * Insert an exercise entry.
     * It can be an exercise set.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $exercise = Exercise::find($request->get('exercise_id'));
        Debugbar::info($request->all());
        if ($request->get('exerciseSet')) {
            // We are inserting an exercise set
            $quantity = $exercise->default_quantity;
            $unit = Unit::find($exercise->default_unit_id);
        }
        else {
            $quantity = $request->get('quantity');
            $unit = Unit::find($request->get('unit_id'));
        }

        $entry = new Entry([
            'date' => $request->get('date'),
            'quantity' => $quantity,
        ]);

        $entry->user()->associate(Auth::user());
        $entry->exercise()->associate($exercise);
        $entry->unit()->associate($unit);

        $entry->save();

        $entry = $this->transform($this->createItem($entry, new ExerciseEntryTransformer))['data'];
        return response($entry, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/exerciseEntries/{exerciseEntries}
    * @param Request $request
    * @param Entry $entry
    * @return Response
    */
    public function update(Request $request, Entry $entry)
    {
        // Create an array with the new fields merged
        $data = array_compare($entry->toArray(), $request->only([
            'quantity'
        ]));

        $entry->update($data);

        $entry = $this->transform($this->createItem($entry, new ExerciseEntryTransformer))['data'];
        return response($entry, Response::HTTP_OK);
    }

    /**
     * DELETE /api/exerciseEntries/{entries}
     * @param Request $request
     * @param Entry $entry
     * @return Response
     */
    public function destroy(Request $request, Entry $entry)
    {
        try {
            $entry->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Entry could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }
            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}