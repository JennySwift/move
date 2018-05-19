<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExerciseStoreRequest;
use App\Http\Transformers\Exercises\ExerciseTransformer;
use App\Http\Transformers\SessionTransformer;
use App\Models\Exercise;
use App\Models\Session;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Tests\ExtendedResponse;


/**
 * Class ExercisesController
 * @package App\Http\Controllers\Exercises
 */
class ExercisesController extends Controller
{

    private $fields = [
        'name',
        'description',
        'priority',
    ];

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
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
                ->orderBy('name')
                ->get();
        }

        return $this->respondIndex($exercises, new ExerciseTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Exercise $exercise
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Exercise $exercise)
    {
        if ($request->get('include') === 'sessions') {
            $sessions = Session::whereHas('exercises', function ($q) use ($exercise) {
                $q->where('exercises.id', $exercise->id)
                    ->where('complete', 1);
            })
                ->with([
                    'exercises' => function ($query) use ($exercise) {
                        $query->where('exercises.id', '=', $exercise->id)
                            ->where('complete', 1);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            return $this->respondShowWithPagination($sessions, new SessionTransformer, ['exercises']);
        }
        else {
            return $this->respondShow($exercise, new ExerciseTransformer);
        }


    }

    /**
     *
     * @param ExerciseStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(ExerciseStoreRequest $request)
    {
        $exercise = new Exercise($request->only($this->fields));
        $exercise->user()->associate(Auth::user());
        $exercise->save();

        return $this->respondStore($exercise, new ExerciseTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Exercise $exercise
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        $data = array_compare($exercise->toArray(), $request->only($this->fields));

        $exercise->update($data);

        return $this->respondUpdate($exercise, new ExerciseTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Exercise $exercise
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, Exercise $exercise)
    {
        return $this->destroyModel($exercise);
    }
}