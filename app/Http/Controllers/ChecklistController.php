<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ChecklistController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function index() {
        $data = array('description'=>'This is checklist');
        return $this->get_response($data);
    }

    public function store(Request $request) {
        $data = $request->json()->get('data')['attributes'];
        $validator = Validator::make(
            $request->json()->get('data')['attributes'],
            [
                'object_domain'=>'string|required|',
                'object_id'=>'integer|required',
                'description'=>'string|required',
                'urgency'=>'nullable|integer',
                'due'=>'nullable|date',
                'task_id'=>'string|required',
                ]
        );
        if($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        } else {
            if(!empty($data['items'])) {
                $data['items'] = array_map('strval',$data['items']);
            }
        }
        return $this->get_response($data);
    }
}

?>