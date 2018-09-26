<?php namespace App\Http\Transformers;

use App\Models\WorkoutGroup;
use League\Fractal\TransformerAbstract;

/**
 * Class WorkoutGroupTransformer
 */
class WorkoutGroupTransformer extends TransformerAbstract
{
    /**
     *
     * @param WorkoutGroup $workoutGroup
     * @return array
     */
    public function transform(WorkoutGroup $workoutGroup)
    {
        return [
            'id' => $workoutGroup->id,
            'order' => $workoutGroup->order,
        ];
    }

}