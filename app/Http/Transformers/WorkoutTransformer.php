<?php namespace App\Http\Transformers;

use App\Http\Transformers\UnitTransformer;
use App\Models\Exercise;
use App\Models\Workout;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseTransformer
 */
class WorkoutTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    protected $availableIncludes = ['exercises'];

    /**
     *
     * @param Workout $workout
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExercises(Workout $workout)
    {
        return $this->collection($workout->exercises, new ExerciseWorkoutTransformer);
    }

    /**
     * ExerciseTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     *
     * @param Workout $workout
     * @return array
     */
    public function transform(Workout $workout)
    {
//        foreach ($workout->exercises as $exercise) {
//            dd($exercise->pivot->quantity);
//        }
        $array = [
            'id' => $workout->id,
            'name' => $workout->name,
        ];

        return $array;
    }
}