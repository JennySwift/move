<?php

namespace App\Http\Transformers;

use App\Models\Exercise;
use App\Models\Unit;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseWorkoutTransformer
 */
class ExerciseWorkoutTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'unit'
    ];

    /**
     * @param Exercise $exercise
     * @return array
     */
    public function transform(Exercise $exercise)
    {
        $array = [
            'id' => $exercise->pivot->id,
            'exercise_id' => $exercise->id,
            'name' => $exercise->name,
            'level' => $exercise->pivot->level,
            'quantity' => $exercise->pivot->quantity,
        ];

        return $array;
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Item
     */
    public function includeUnit(Exercise $exercise)
    {
        $unit = Unit::find($exercise->pivot->unit_id);

        return $this->item($unit, new UnitTransformer);
    }

}