<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SessionStoreRequest;
use App\Http\Transformers\SessionTransformer;
use App\Models\Session;
use App\Models\Workout;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class SessionsController
 * @package App\Http\Controllers\API
 */
class SessionsController extends Controller
{
    private $fields = ['name'];

    /**
     * GET /api/sessions
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $max = $request->get('max') ? $request->get('max') : 14;
        $sessions = Session::forCurrentUser()->orderBy('created_at', 'desc')->simplePaginate($max);

        return response(
            [
                'data' => $this->transform($this->createCollection($sessions, new SessionTransformer))['data'],
                'pagination' => $this->getPaginationProperties($sessions)
            ],
            Response::HTTP_OK
        );
    }

    /**
     *
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Session $session)
    {
        if ($request->get('include') === 'exercises') {
            return $this->respondShow($session, new SessionTransformer, ['exercises']);
        }
        else {
            return $this->respondShow($session, new SessionTransformer);
        }
    }



    /**
     *
     * @param SessionStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(SessionStoreRequest $request)
    {
        if (!$request->has('workout_id')) {
            $session = new Session($request->only($this->fields));
            $session->user()->associate(Auth::user());
            $session->save();

            $session = $this->transform($this->createItem($session, new SessionTransformer))['data'];

            return response($session, Response::HTTP_CREATED);
        }
        else {
            //Start an session from a saved workout
            $workout = Workout::forCurrentUser()->findOrFail($request->get('workout_id'));

            $session = new Session(['name' => $workout->name]);
            $session->user()->associate(Auth::user());
            $session->save();

            foreach ($workout->exercises as $exercise) {
                $session->exercises()->attach($exercise->id, [
                    'level' => $exercise->pivot->level,
                    'quantity' => $exercise->pivot->quantity,
                    'complete' => 0,
                    'unit_id' => $exercise->pivot->unit_id,
                ]);
            }

            $session = $this->transform($this->createItem($session, new SessionTransformer), ['exercises'])['data'];
            return response($session, Response::HTTP_CREATED);
        }

    }

    /**
     * UPDATE /api/sessions/{sessions}
     * @param Request $request
     * @param Session $session
     * @return Response
     * @Todo: check unit ids are foreign keys belonging to user before syncing?
     */
    public function update(Request $request, Session $session)
    {
        $data = $this->getData($session, $request->only($this->fields));
        $session->update($data);

        if ($request->get('include') === 'exercises') {
            if ($request->has('exercises')) {
                //I need to detach before syncing, otherwise if there is more than one set of an exercise
                //in the session, it syncs all sets, which is not the behaviour I want.
                $session->exercises()->detach();

                foreach ($request->get('exercises') as $exercise) {
                    $session->exercises()->attach($exercise['exercise_id'], [
                        'level' => $exercise['level'],
                        'quantity' => $exercise['quantity'],
                        'complete' => $exercise['complete'],
                        'unit_id' => $exercise['unit_id'],
                    ]);
                }

            }

//            dd($session->exercises);

            $session = $this->transform($this->createItem($session, new SessionTransformer), ['exercises'])['data'];
            return response($session, Response::HTTP_OK);
        }

        return $this->respond($session, new SessionTransformer, 200);
    }

    /**
     *
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, Session $session)
    {
        return $this->destroyModel($session, 'session');
    }
}