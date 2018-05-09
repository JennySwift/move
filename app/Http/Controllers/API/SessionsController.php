<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SessionStoreRequest;
use App\Http\Transformers\SessionTransformer;
use App\Models\Session;
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
            $session = $this->transform($this->createItem($session, new SessionTransformer), ['exercises'])['data'];
        }
        else {
            $session = $this->transform($this->createItem($session, new SessionTransformer))['data'];
        }


        return response($session, Response::HTTP_OK);
    }



    /**
     *
     * @param SessionStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(SessionStoreRequest $request)
    {
        $session = new Session($request->only($this->fields));
        $session->user()->associate(Auth::user());
        $session->save();

        $session = $this->transform($this->createItem($session, new SessionTransformer))['data'];


        return response($session, Response::HTTP_CREATED);
    }

    /**
     *
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, Session $session)
    {
        return $this->destroyModel($session, 'session');
    }
}