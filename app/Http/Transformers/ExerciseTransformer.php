<?php namespace App\Http\Transformers\Exercises;

use App\Http\Transformers\TagTransformer;
use App\Http\Transformers\UnitTransformer;
use App\Models\Exercise;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseTransformer
 */
class ExerciseTransformer extends TransformerAbstract
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
     * @param Exercise $exercise
     * @return array
     */
    public function transform(Exercise $exercise)
    {
        $array = [
            'id' => $exercise->id,
            'name' => $exercise->name,
            'description' => $exercise->description,
            'priority' => $exercise->priority,
        ];

        return $array;
    }
}