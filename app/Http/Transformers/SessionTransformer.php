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
    protected $availableIncludes = [''];

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
     * @return array
     */
    public function transform(Session $session)
    {
        $array = [
            'id' => $session->id,
            'name' => $session->name,
            'created_at' => $session->created_at->format('Y-m-d'),
        ];

        return $array;
    }
}