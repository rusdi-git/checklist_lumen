<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class Controller extends BaseController
{
    public function get_response($data)
    {
        $content = array('data'=>$data);
        return response($content);
    }

    /**
     * @param $message
     * @return Response
     */
    protected function respondWithMissingField($message)
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
        ], 400);
    }

    /**
     * @param $message
     * @return Response
     */
    private function respondWithValidationError($message)
    {
        return response()->json([
            'status' => 406,
            'message' => $message,
        ], 406);
    }

    /**
     * @param $validator
     * @return Response
     */
    protected function respondWithErrorMessage($validator)
    {
        $required = $messages = [];
        $validatorMessages = $validator->errors()->toArray();
        foreach($validatorMessages as $field => $message) {
            if (strpos($message[0], 'required')) {
                $required[] = $field;
            }

            foreach ($message as $error) {
                $messages[] = $error;
            }
        }

        if (count($required) > 0) {
            $fields = implode(', ', $required);
            $message = "Missing required fields $fields";

            return $this->respondWithMissingField($message);
        }


        return $this->respondWithValidationError(implode(', ', $messages));
    }

    private function getFractalManager()
    {
        $request = app(Request::class);
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer('http://www.localhost:8000'));

        if (!empty($request->query('include'))) {
            $manager->parseIncludes($request->query('include'));
        }
        return $manager;
    }

    public function item($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Item($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    public function collection($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Collection($data, $transformer, $transformer->type);
        return $manager->createData($resource)->toArray();
    }

    /**
     * @param LengthAwarePaginator $data
     * @param $transformer
     * @return array
     */
    public function paginate($data, $transformer)
    {
        $manager = $this->getFractalManager();
        $resource = new Collection($data, $transformer, $transformer->type);
        $resource->setPaginator(new IlluminatePaginatorAdapter($data));
        return $manager->createData($resource)->toArray();
    }
}
