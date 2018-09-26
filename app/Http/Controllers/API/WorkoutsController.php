<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutStoreRequest;
use App\Http\Transformers\WorkoutTransformer;
use App\Models\Workout;
use Auth;
use Illuminate\Http\Request;


/**
 * Class WorkoutsControllerController
 * @package App\Http\Controllers\Exercises
 */
class WorkoutsController extends Controller
{
    private $fields = ['name'];

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $workouts = Workout::forCurrentUser()->get();

        return $this->respondIndex($workouts, new WorkoutTransformer);
    }

    /**
     *
     * @param WorkoutStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(WorkoutStoreRequest $request)
    {
        $workout = new Workout($request->only($this->fields));
        $workout->user()->associate(Auth::user());
        $workout->save();

        return $this->respondStore($workout, new WorkoutTransformer);

    }

    /**
     *
     * @param Request $request
     * @param Workout $workout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Workout $workout)
    {
        if ($request->get('include') === 'exercises') {
            return $this->respondShow($workout, new WorkoutTransformer, ['exercises']);
        } else {
            return $this->respondShow($workout, new WorkoutTransformer);
        }
    }

    /**
     * @Todo: check unit ids are foreign keys belonging to user before syncing?
     * @param Request $request
     * @param Workout $workout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Workout $workout)
    {
        $data = $this->getData($workout, $request->only($this->fields));
        $workout->update($data);

        if ($request->get('include') === 'exercises') {
            //Updating all the exercises in a workout at once
            if ($request->has('exercises') && !$request->has('exercise_id')) {
                //I need to detach before syncing, otherwise if there is more than one set of an exercise
                //in the workout, it syncs all sets, which is not the behaviour I want.
                $workout->exercises()->detach();

                foreach ($request->get('exercises') as $exercise) {
                    $workout->exercises()->attach($exercise['exercise_id'], [
                        'level' => $exercise['level'],
                        'quantity' => $exercise['quantity'],
                        'unit_id' => $exercise['unit_id'],
                        'workout_group_id' => $exercise['workout_group_id'],
                    ]);
                }
            }
            //Updating the sets for just one exercise in a workout
            else if ($request->has('exercise_id')) {
                $exerciseId = $request->get('exercise_id');
                $unitId = $request->get('unit_id');
                //Todo: If the workout has an exercise with one unit, and the same exercise with another unit, both kinds will be deleted
                $workout->exercises()->detach($exerciseId);

                foreach ($request->get('exercises') as $exercise) {
                    $workout->exercises()->attach($exerciseId, [
                        'level' => $exercise['level'],
                        'quantity' => $exercise['quantity'],
                        'unit_id' => $unitId,
                        'workout_group_id' => $exercise['workout_group_id'],
                    ]);
                }
            }

            return $this->respondUpdate($workout, new WorkoutTransformer, ['exercises']);
        }

        return $this->respondUpdate($workout, new WorkoutTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Workout $workout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, Workout $workout)
    {
        return $this->destroyModel($workout, 'workout');
    }
}