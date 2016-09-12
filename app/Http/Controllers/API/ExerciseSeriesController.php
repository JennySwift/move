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
        $series = $this->transform($this->createCollection($this->exerciseSeriesRepository->getExerciseSeries(), new SeriesTransformer))['data'];
        return response($series, Response::HTTP_OK);
    }


    /**
     * For the exercise series popup
     * @param Series $series
     * @return array
     */
    public function show(Series $series)
    {
        return transform(createItem($series, new SeriesTransformer))['data'];
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
     *
     * @param Request $request
     * @param Series $series
     * @return mixed
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

        return $this->responseOkWithTransformer($series, new SeriesTransformer);
    }

    /**
     *
     * @param Series $series
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Series $series)
    {
        try {
            $series->delete();

            return $this->responseNoContent();
        } catch (\Exception $e) {
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
