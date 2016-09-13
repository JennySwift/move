<?php

namespace App\Http\Transformers\Exercises;

use App\Models\Series;
use League\Fractal\TransformerAbstract;

/**
 * Class SeriesTransformer
 */
class SeriesTransformer extends TransformerAbstract
{
    /**
     * Todo: This isn't needed all the time
     * @var array
     */
    protected $availableIncludes = ['exercises'];

    /**
     * @param Series $series
     * @return array
     */
    public function transform(Series $series)
    {
        $array = [
            'id' => $series->id,
            'name' => $series->name,
            'priority' => $series->priority,
            'lastDone' => $series->lastDone
        ];

        return $array;
    }

    /**
     *
     * @param Series $series
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExercises(Series $series)
    {
        return createCollection($series->exercises, new ExerciseTransformer);
    }

}