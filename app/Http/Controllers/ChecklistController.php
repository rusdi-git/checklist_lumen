<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Checklist;
use App\Http\Transformers\ChecklistTransformer;

class ChecklistController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index() {
        $data = Checklist::all();
        return $this->collection($data,new ChecklistTransformer());
    }

    public function store(Request $request) {
        $data = $request->json()->get('data')['attributes'];
        $validator = Validator::make(
            $data,
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

        $checklist = new Checklist();
        $checklist->object_domain = $data['object_domain'];
        $checklist->object_id = $data['object_id'];
        $checklist->description = $data['description'];
        $checklist->due = $data['due'];
        $checklist->urgency = $data['urgency'];
        $checklist->task_id = $data['task_id'];
        $checklist->save();

        return $this->item($checklist,new ChecklistTransformer(),201);
    }

    public function show($id) {
        $data = Checklist::find($id);
        if(!$data) {
            abort(404,'Checklist not found.');
        }
        return $this->item($data,new ChecklistTransformer());
    }

    public function edit($id, Request $request) {
        $checklist = Checklist::find($id);
        if(!$checklist) {
            abort(404,'Not Found.');
        }
        $data = $request->json()->get('data')['attributes'];
        $validator = Validator::make(
            $data,
            [
                'object_domain'=>'string|',
                'object_id'=>'integer',
                'description'=>'string',
                'urgency'=>'nullable|integer',
                'due'=>'nullable|date',
                'task_id'=>'string',
                ]
        );
        if($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        } else {
            if(!empty($data['items'])) {
                $data['items'] = array_map('strval',$data['items']);
            }
        }
        
        $fields = ['object_domain','object_id','description','urgency','due','task_id'];
        foreach($data as $key=>$value) {
            if(in_array($key,$fields)) {
                $checklist->$key = $value;
            }
        }
        $checklist->update();

        return $this->item($checklist,new ChecklistTransformer());
    }

    public function remove($id) {
        $checklist = Checklist::find($id);
        if(!$checklist) {
            abort(404,'Not Found.');
        }
        $checklist->delete();
        return $this->get_response(['result'=>'Checklist has been deleted'],204);
    }
}

?>