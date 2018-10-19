<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\WorkoutTransformer;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutGroup;
use App\Repositories\WorkoutGroupsRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;


class WorkoutsExercisesController extends Controller
{

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
     * Updating the sets for just one exercise in a workout
     * @Todo: check unit ids are foreign keys belonging to user before syncing?
     * @param Request $request
     * @param Workout $workout
     * api/workouts/{workout}/exercises/{exercise}
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Workout $workout)
    {
        if ($request->get('include') === 'exercises') {
            if ($request->has('createExerciseGroup')) {
                $this->workoutGroupsRepository->createWorkoutGroup($workout);
            }

            if ($request->has('exercise_id')) {
                $exerciseId = $request->get('exercise_id');
                //Todo: If the workout has an exercise with one unit, and the same exercise with another unit, both kinds will be deleted
                $workout->exercises()->detach($exerciseId);

                foreach ($request->get('exercises') as $exercise) {
                    //Commenting this out, since doing too much here. Workout should be created in separate API call, before updating here.
//                    $workoutGroupId = $exercise['workout_group_id'] ? $exercise['workout_group_id'] : $this->workoutGroupsRepository->createWorkoutGroup($workout)->id;
                    $workout->exercises()->attach($request->get('exercise_id'), [
                        'level' => $exercise['level'],
                        'quantity' => $exercise['quantity'],
                        'unit_id' => $request->get('unit_id'),
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
     */
    public function store(Request $request, Workout $workout)
    {
        $workout->exercises()->attach($request->get('exercise_id'), [
            'level' => $request->get('level'),
            'quantity' => $request->get('quantity'),
            'unit_id' => $request->get('unit_id'),
            'workout_group_id' => $this->workoutGroupsRepository->createWorkoutGroup($workout)->id,
        ]);

        return $this->respondStore($workout, new WorkoutTransformer, ['exercises']);
    }

    /**
     * Delete just one set of an exercise in a workout
     * @param Request $request
     * @param Workout $workout
     * @param Exercise $exercise
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, Workout $workout, Exercise $exercise)
    {
        $setToDelete = $workout->exercises()->wherePivot('id', $request->get('id'))->detach();
//        dd($setToDelete);
//        $setToDelete = DB::table('exercise_workout')
//            ->find($request->get('id'));
//        $setToDelete->delete();

        $this->workoutGroupsRepository->deleteUnused($workout);

        return response([], Response::HTTP_NO_CONTENT);
    }


}