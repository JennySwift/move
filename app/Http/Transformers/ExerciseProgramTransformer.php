<?php

namespace App\Http\Transformers\Exercises;

use App\Models\ExerciseProgram;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseProgramTransformer
 */
class ExerciseProgramTransformer extends TransformerAbstract
{
    /**
     * @param ExerciseProgram $exerciseProgram
     * @return array
     */
    public function transform(ExerciseProgram $exerciseProgram)
    {
        $array = [
            'id' => $exerciseProgram->id,
            'name' => $exerciseProgram->name,
        ];

        return $array;
    }

}