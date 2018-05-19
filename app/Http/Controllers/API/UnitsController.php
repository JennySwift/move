<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Transformers\UnitTransformer;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;

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
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $units = Unit::forCurrentUser()
            ->orderBy('name', 'asc')
            ->get();


        return $this->respondIndex($units, new UnitTransformer);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $unit = new Unit([
            'name' => $request->get('name'),
        ]);

        $unit->user()->associate(Auth::user());
        $unit->save();

        return $this->respondStore($unit, new UnitTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Unit $unit
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Unit $unit)
    {
        // Create an array with the new fields merged
        $data = array_compare($unit->toArray(), $request->only([
            'name'
        ]));


        $unit->update($data);

        return $this->respondUpdate($unit, new UnitTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Unit $unit
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, Unit $unit)
    {
        return $this->destroyModel($unit);
    }

}