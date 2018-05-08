<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutStoreRequest;
use App\Http\Transformers\WorkoutTransformer;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;


/**
 * Class WorkoutsControllerController
 * @package App\Http\Controllers\Exercises
 */
class WorkoutsController extends Controller
{
    private $fields = ['name'];

    /**
     * GET /api/workouts
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $workouts = Workout::forCurrentUser()->get();

        $workouts = $this->transform($this->createCollection($workouts, new WorkoutTransformer))['data'];
        return response($workouts, Response::HTTP_OK);
    }

    /**
     * POST /api/workouts
     * @param WorkoutStoreRequest $request
     * @return Response
     */
    public function store(WorkoutStoreRequest $request)
    {
        $workout = new Workout($request->only($this->fields));
        $workout->user()->associate(Auth::user());
        $workout->save();

        $workout = $this->transform($this->createItem($workout, new WorkoutTransformer))['data'];


        return response($workout, Response::HTTP_CREATED);
    }

    /**
     * GET /api/workouts/{workouts}
     * @param Request $request
     * @param Workout $workout
     * @return Response
     */
    public function show(Request $request, Workout $workout)
    {
        if ($request->get('include') === 'exercises') {
            $workout = $this->transform($this->createItem($workout, new WorkoutTransformer), ['exercises'])['data'];
        }
        else {
            $workout = $this->transform($this->createItem($workout, new WorkoutTransformer))['data'];
        }


        return response($workout, Response::HTTP_OK);
    }

    /**
    * UPDATE /api/workouts/{workouts}
    * @param Request $request
    * @param Workout $workout
    * @return Response
     * @Todo: check unit ids are foreign keys belonging to user before syncing?
    */
    public function update(Request $request, Workout $workout)
    {
        $data = $this->getData($workout, $request->only($this->fields));
        $workout->update($data);

        if ($request->get('include') === 'exercises') {
            if ($request->has('exercises')) {
                //I need to detach before syncing, otherwise if there is more than one set of an exercise
                //in the workout, it syncs all sets, which is not the behaviour I want.
                $workout->exercises()->detach();

                foreach ($request->get('exercises') as $exercise) {
                    $workout->exercises()->attach($exercise['id'], [
                        'level' => $exercise['level'],
                        'quantity' => $exercise['quantity'],
                        'unit_id' => $exercise['unit_id'],
                    ]);
                }

            }

//            dd($workout->exercises);

            $workout = $this->transform($this->createItem($workout, new WorkoutTransformer), ['exercises'])['data'];
            return response($workout, Response::HTTP_OK);
        }

        return $this->respond($workout, new WorkoutTransformer, 200);
    }

    /**
     * DELETE /api/workouts/{workouts}
     * @param Request $request
     * @param Workout $workout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, Workout $workout)
    {
        return $this->destroyModel($workout, 'workout');
    }
}