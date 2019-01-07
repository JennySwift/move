<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutStoreRequest;
use App\Http\Transformers\WorkoutTransformer;
use App\Models\Workout;
use App\Models\WorkoutGroup;
use App\Repositories\WorkoutGroupsRepository;
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
     * @var
     */
    private $workoutGroupsRepository;

    /**
     * WorkoutsExercisesController constructor.
     * @param WorkoutGroupsRepository $workoutGroupsRepository
     */
    public function __construct(WorkoutGroupsRepository $workoutGroupsRepository) {
        $this->workoutGroupsRepository = $workoutGroupsRepository;
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $workouts = Workout::forCurrentUser()->orderBy('name', 'asc')->get();

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
     * @param Request $request
     * @param Workout $workout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Workout $workout)
    {
        $data = $this->getData($workout, $request->only($this->fields));
        $workout->update($data);

        //Updating all the exercises in a workout at once
        if ($request->has('exercises') && !$request->has('exercise_id')) {
            //I need to detach before syncing, otherwise if there is more than one set of an exercise
            //in the workout, it syncs all sets, which is not the behaviour I want.
            $workout->exercises()->detach();

            foreach ($request->get('exercises') as $exercise) {
                $workoutGroupId = $exercise['workout_group_id'] ? $exercise['workout_group_id'] : $this->workoutGroupsRepository->createWorkoutGroup($workout)->id;
                $workout->exercises()->attach($exercise['exercise_id'], [
                    'level' => $exercise['level'],
                    'quantity' => $exercise['quantity'],
                    'unit_id' => $exercise['unit_id'],
                    'workout_group_id' => $workoutGroupId,
                ]);
            }
            $this->workoutGroupsRepository->deleteUnused($workout);

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