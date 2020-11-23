<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Checklist;
use App\Models\Item;
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
        $user = Auth::user();
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
        }

        $checklist = new Checklist();
        $checklist->object_domain = $data['object_domain'];
        $checklist->object_id = $data['object_id'];
        $checklist->description = $data['description'];
        $checklist->due = $data['due'];
        $checklist->urgency = $data['urgency'];
        $checklist->task_id = $data['task_id'];
        $checklist->created_by = $user->id;
        $checklist->updated_by = $user->id;
        $checklist->save();

        if(!empty($data['items'])) {
            $inserted_item = array();
            $created_at = Carbon::now();
            foreach($data['items'] as $item) {
                $inserted_item[] = [
                    'checklist_id'=>$checklist->id,
                    'description'=>strval($item),
                    'due'=>$checklist->due,
                    'urgency'=>$checklist->urgency,
                    'task_id'=>$checklist->task_id,
                    'created_at'=>$created_at,
                    'created_by'=>$user->id,
                    'updated_at'=>$created_at,
                    'updated_by'=>$user->id,
                ];
            }
            Item::insert($inserted_item);
        }

        return $this->item($checklist,new ChecklistTransformer(),201);
    }

    public function show($id) {
        $checklist = Checklist::find($id);
        if(!$checklist) {
            abort(404,'Checklist not found.');
        }
        return $this->item($checklist,new ChecklistTransformer());
    }

    public function edit($id, Request $request) {
        $checklist = Checklist::find($id);
        $user = Auth::user();
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
        }
        
        $fields = ['object_domain','object_id','description','urgency','due','task_id'];
        foreach($data as $key=>$value) {
            if(in_array($key,$fields)) {
                $checklist->$key = $value;
            }
        }
        $checklist->updated_by = $user->id;
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