<?php namespace App\Http\Transformers;

use App\Models\Session;
use League\Fractal\TransformerAbstract;

/**
 * Class SessionTransformer
 * @package App\Http\Transformers
 */
class SessionTransformer extends TransformerAbstract
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
     * ExerciseTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     *
     * @param Session $session
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExercises(Session $session)
    {
        return $this->collection($session->exercises, new ExerciseSessionTransformer);
    }

    /**
     *
     * @param Session $session
     * @return array
     */
    public function transform(Session $session)
    {
        $array = [
            'id' => $session->id,
            'name' => $session->name,
            'created_at' => $session->created_at->format('Y-m-d H:i:s'),
        ];

        return $array;
    }
}