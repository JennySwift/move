<?php

namespace App\Http\Transformers\Exercises;

use App\Http\Transformers\UnitTransformer;
use App\Models\Entry;
use League\Fractal\TransformerAbstract;

/**
 * Class ExerciseEntryTransformer
 */
class ExerciseEntryTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['exercise', 'unit'];

    /**
     * @VP:
     * When I include the exercise like this, there's heaps of data
     * I don't need in the response
     * (for example I don't need the program of the exercise),
     * whereas if I do just what I need
     * with 'exercise' => [...],
     * it seems inconsistent with my other responses,
     * and therefore more fiddly to test.
     * @param Entry $entry
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExercise(Entry $entry)
    {
        return $this->item($entry->exercise, new ExerciseTransformer);
    }

    /**
     *
     * @param Entry $entry
     * @return \League\Fractal\Resource\Item
     */
    public function includeUnit(Entry $entry)
    {
        return $this->item($entry->unit, new UnitTransformer);
    }

    /**
     * @param Entry $entry
     * @return array
     */
    public function transform(Entry $entry)
    {
        $sets = $entry->sets ? $entry->sets : $entry->calculateSets($entry->date);
        $total = $entry->total ? $entry->total : $entry->calculateTotal($entry->date);

        $array = [
            'id' => $entry->id,
            'date' => $entry->date,
            //Todo: is this needed on the entry? Isn't it for the exercise?
            'daysAgo' => $entry->days_ago,
            'sets' => $sets,
            'total' => $total,
            'quantity' => $entry->quantity,
            'createdAt' => $entry->created_at->format('h:ia')
        ];

        return $array;
    }

}