<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\UnitTransformer;
use App\Models\Unit;
use App\Repositories\UnitsRepository;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;

/**
 * @VP:
 * This is much the same as FoodUnitsController. I did it so I could have
 * an index method for both food and exercise units. Good idea or not?
 */


/**
 * Class ExerciseUnitsController
 * @package App\Http\Controllers\Exercises
 */
class UnitsController extends Controller
{
    /**
     * GET /api/units
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $units = Unit::forCurrentUser()
            ->where('for', 'exercise')
            ->orderBy('name', 'asc')
            ->get();

        $units = $this->transform($this->createCollection($units, new UnitTransformer))['data'];
        return response($units, Response::HTTP_OK);
    }

    /**
     * POST /api/exerciseUnits
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $unit = new Unit([
            'name' => $request->get('name'),
            'for' => 'exercise'
        ]);

        $unit->user()->associate(Auth::user());
        $unit->save();

        $unit = $this->transform($this->createItem($unit, new UnitTransformer))['data'];
        return response($unit, Response::HTTP_CREATED);
    }

    /**
    * UPDATE /api/units/{unit}
    * @param Request $request
    * @param Unit $unit
    * @return Response
    */
    public function update(Request $request, Unit $unit)
    {
        // Create an array with the new fields merged
        $data = array_compare($unit->toArray(), $request->only([
            'name'
        ]));


        $unit->update($data);

        $unit = $this->transform($this->createItem($unit, new UnitTransformer))['data'];
        return response($unit, Response::HTTP_OK);
    }

    /**
     * DELETE /api/exerciseUnits/{exerciseUnit}
     * @param Request $request
     * @param Unit $unit
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, Unit $unit)
    {
        return $this->destroyModel($unit);
    }

}