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
    */
    public function update(Request $request, Workout $workout)
    {
        $data = $this->getData($workout, $request->only($this->fields));
        $workout->update($data);

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