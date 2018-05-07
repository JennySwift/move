<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\ExerciseStoreRequest;
use App\Http\Transformers\Exercises\ExerciseTransformer;
use App\Models\Exercise;
use App\Models\Series;
use App\Models\Unit;
use Auth;
use DB;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;


/**
 * Class ExercisesController
 * @package App\Http\Controllers\Exercises
 */
class ExercisesController extends Controller
{
    /**
     * GET /api/exercises
     * Get all exercises for the current user,
     * along with their tags, default unit name
     * and the name of the series each exercise belongs to.
     * Order first by series name, then by step number.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->get('typing')) {
            $exercises = Exercise::forCurrentUser()
                ->where('name', 'LIKE', '%' . $request->get('typing') . '%')
                ->get();
        }
        else {
            $exercises = Exercise::forCurrentUser('exercises')
                ->with('defaultUnit')
                ->orderBy('step_number')
                ->with('series')
                ->get();
        }

        $exercises = $this->transform($this->createCollection($exercises, new ExerciseTransformer))['data'];
        return response($exercises, Response::HTTP_OK);
    }

    /**
     * GET /api/exercises/{exercises}
     * @param Request $request
     * @param Exercise $exercise
     * @return Response
     */
    public function show(Request $request, Exercise $exercise)
    {
        $exercise = $this->transform($this->createItem($exercise, new ExerciseTransformer))['data'];
        return response($exercise, Response::HTTP_OK);
    }

    /**
     *
     * @param ExerciseStoreRequest $request
     * @return Response
     */
    public function store(ExerciseStoreRequest $request)
    {
        $exercise = new Exercise($request->only(
            'name',
            'description',
            'step_number',
            'default_quantity',
            'target',
            'priority',
            'frequency'
        ));
        $exercise->user()->associate(Auth::user());
        $exercise->series()->associate(Series::find($request->get('series_id')));
        $exercise->defaultUnit()->associate(Unit::find($request->get('default_unit_id')));
        $exercise->save();

        $exercise = $this->transform($this->createItem($exercise, new ExerciseTransformer))['data'];

        return response($exercise, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/exercises/{exercises}
    * @param Request $request
    * @param Exercise $exercise
    * @return Response
    */
    public function update(Request $request, Exercise $exercise)
    {
        // Create an array with the new fields merged
        $data = array_compare($exercise->toArray(), $request->only([
            'name'
        ]));

        $exercise->update($data);

        // Create an array with the new fields merged
        $data = array_compare($exercise->toArray(), $request->only([
            'name',
            'step_number',
            'default_quantity',
            'description',
            'target',
            'priority',
            'frequency'
        ]));

        $exercise->update($data);

        if ($request->has('series_id')) {
            $series = Series::findOrFail($request->get('series_id'));
            $exercise->series()->associate($series);
            $exercise->save();
        }

        if ($request->has('default_unit_id')) {
            $unit = Unit::findOrFail($request->get('default_unit_id'));
            $exercise->defaultUnit()->associate($unit);
            $exercise->save();
        }

        $exercise = $this->transform($this->createItem($exercise, new ExerciseTransformer))['data'];
        return response($exercise, Response::HTTP_OK);
    }

    /**
     * DELETE /api/exercises/{exercises}
     * @param Request $request
     * @param Exercise $exercise
     * @return Response
     */
    public function destroy(Request $request, Exercise $exercise)
    {
        try {
            $exercise->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Exercise could not be deleted. It is in use.';
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