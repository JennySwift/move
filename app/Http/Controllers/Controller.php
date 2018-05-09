<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use ReflectionClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @param $array
     * @return array
     */
    public function getPaginationProperties($array)
    {
        return [
//            'total' => $array->total(),
            'per_page' => $array->perPage(),
            'current_page' => $array->currentPage(),
//            'last_page' => $array->lastPage(),
            'next_page_url' => $array->nextPageUrl(),
            'prev_page_url' => $array->previousPageUrl(),
            'from' => $array->firstItem(),
            'to' => $array->lastItem(),
        ];
    }

    /**
     *
     * @param Model $model
     * @param array $fields
     * @return array
     */
    public function getData(Model $model, array $fields)
    {
        return array_compare($model->toArray(), $fields);
    }

    /**
     *
     * @param $resource
     * @param TransformerAbstract $transformer
     * @param $responseCode
     * @param array|null $includes
     * @param Request $request
     * @return Response
     */
    public function respond(
        $resource,
        TransformerAbstract $transformer,
        $responseCode,
        array $includes = null,
        Request $request = null
    ) {
        if ($resource instanceof EloquentCollection) {
            $resource = $this->transform($this->createCollection($resource, $transformer), $includes, $request)['data'];
        }
        else {
            $resource = $this->transform($this->createItem($resource, $transformer), $includes, $request)['data'];
        }

        return response($resource, $responseCode);
    }

    /**
     *
     * @param Model $model
     * @param null $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function destroyModel(Model $model, $name = null)
    {
        try {
            $model->delete();

            return response([], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {

            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $name = (new \ReflectionClass($model))->getShortName();
                $message = $name . ' could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }

            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Model $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Item
     */
    function createItem($model, TransformerAbstract $transformer, $key = null)
    {
        return new Item($model, $transformer, $key);
    }

    /**
     * For Fractal transformer
     * @param $resource
     * @param null $includes
     * @param Request $request
     * @return array
     */
    public function transform($resource, $includes = null, Request $request = null)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

        //Includes passed to this method as a parameter
        if ($includes) {
            $manager->parseIncludes($includes);
        }

        //Includes in url
        if ($request && $request->has('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection($model, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($model, $transformer, $key);
    }

    /**
     * For Fractal transformer
     * @param EloquentCollection $collection
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
//    public function createCollection(EloquentCollection $collection, TransformerAbstract $transformer, $key = null)
//    {
//        return new Collection($collection, $transformer, $key);
//    }
}