<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Exercises\SeriesTransformer;
use App\Models\Series;
use App\Repositories\ExerciseSeriesRepository;
use App\Repositories\WorkoutsRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ExerciseSeriesController
 * @package App\Http\Controllers\Exercises
 */
class ExerciseSeriesController extends Controller
{
    /**
     * @var ExerciseSeriesRepository
     */
    private $exerciseSeriesRepository;
    /**
     * @var WorkoutsRepository
     */
    private $workoutsRepository;

    /**
     * @param ExerciseSeriesRepository $exerciseSeriesRepository
     * @param WorkoutsRepository $workoutsRepository
     */
    public function __construct(
        ExerciseSeriesRepository $exerciseSeriesRepository,
        WorkoutsRepository $workoutsRepository
    ) {
        $this->exerciseSeriesRepository = $exerciseSeriesRepository;
        $this->workoutsRepository = $workoutsRepository;
    }

    /**
     * GET /api/series
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $series = Series::forCurrentUser('exercise_series')->orderBy('name', 'asc')->get();
        $series = $this->transform($this->createCollection($series, new SeriesTransformer))['data'];

        return response($series, Response::HTTP_OK);
    }

    /**
     * GET /api/series/{series}
     * @param Request $request
     * @param Series $series
     * @return Response
     */
    public function show(Request $request, Series $series)
    {
        $series = $this->transform($this->createItem($series, new SeriesTransformer))['data'];
        return response($series, Response::HTTP_OK);
    }

    /**
     * POST /api/series
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $series = new Series($request->only([
            'name',
            'priority'
        ]));
        $series->user()->associate(Auth::user());

        $series->save();

        $series = $this->transform($this->createItem($series, new SeriesTransformer))['data'];
        return response($series, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/series/{series}
    * @param Request $request
    * @param Series $series
    * @return Response
    */
    public function update(Request $request, Series $series)
    {
        // Create an array with the new fields merged
        $data = array_compare($series->toArray(), $request->only([
            'name',
            'priority'
        ]));
//        dd($data);

        $series->update($data);

        if ($request->has('workout_ids')) {
            $series->workouts()->sync($request->get('workout_ids'));
//            $series->save();
        }

        $series = $this->transform($this->createItem($series, new SeriesTransformer))['data'];
        return response($series, Response::HTTP_OK);
    }

    /**
     * DELETE /api/exerciseSeries/{series}
     * @param Request $request
     * @param Series $series
     * @return Response
     */
    public function destroy(Request $request, Series $series)
    {
        try {
            $series->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Series could not be deleted. It is in use.';
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
