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
        $array = [
            'id' => $workout->id,
            'name' => $workout->name,
        ];

        return $array;
    }
}