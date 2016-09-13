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
    protected $defaultIncludes = ['defaultUnit', 'program', 'series'];

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
            'stepNumber' => $exercise->step_number,
            'defaultQuantity' => $exercise->default_quantity,
//            'tag_ids' => $exercise->tags()->lists('id'),
            'target' => $exercise->target,
            'priority' => $exercise->priority,
            'lastDone' => $exercise->lastDone,
            'stretch' => $exercise->stretch,
            'frequency' => $exercise->frequency,
            'dueIn' => $exercise->dueIn,
        ];

        return $array;
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Item
     */
    public function includeDefaultUnit(Exercise $exercise)
    {
        if ($exercise->defaultUnit) {
            return createItem($exercise->defaultUnit, new UnitTransformer);
        }
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Item
     */
    public function includeProgram(Exercise $exercise)
    {
        if ($exercise->program) {
            return createItem($exercise->program, new ExerciseProgramTransformer);
        }
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Item
     */
    public function includeSeries(Exercise $exercise)
    {
        if ($exercise->series) {
            return createItem($exercise->series, new SeriesTransformer);
        }
    }

    /**
     *
     * @param Exercise $exercise
     * @return \League\Fractal\Resource\Collection
     */
//    public function includeTags(Exercise $exercise)
//    {
//        $tags = $exercise->tags;
//
//        return createCollection($tags, new TagTransformer);
//    }

}