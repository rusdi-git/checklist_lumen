<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Checklist;
use App\Models\Item;
use App\Http\Transformers\ChecklistTemplateTransformer;

class ChecklistTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function index() {
    //     $data = Checklist::all();
    //     return $this->collection($data,new ChecklistTransformer());
    // }

    public function store(Request $request) {
        $data = $request->json()->get('data')['attributes'];
        $validator = Validator::make(
            $data,
            [
                'name'=>'string|required',
                'checklist.description'=>'string|required',
                'checklist.due_interval'=>'nullable|integer',
                'checklist.due_unit'=>[
                    'nullable','string',Rule::in(['minute','hour','day','week','month'])
                ],
                'items.*.description'=>'string|required',
                'items.*.urgency'=>'nullable|integer',
                'items.*.due_interval'=>'nullable|integer',
                'items.*.due_unit'=>[
                    'nullable','string',Rule::in(['minute','hour','day','week','month'])
                ]
            ]
        );
        if($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }
        $checklist = new Checklist();
        $checklist->template_name = $data['name'];
        $checklist->description = $data['checklist']['description'];
        $checklist->due_interval = $data['checklist']['due_interval'];
        $checklist->due_unit = $data['checklist']['due_unit'];
        $checklist->is_template = true;
        $checklist->save();

        if(!empty($data['items'])) {
            foreach($data['items'] as $item) {
                $checklist_item = new Item();
                $checklist_item->checklist_id = $checklist->id;
                $checklist_item->description = strval($item['description']);
                $checklist_item->urgency = $item['urgency'];
                $checklist_item->due_interval = $item['due_interval'];
                $checklist_item->due_unit = $item['due_unit'];
                $checklist_item->save();
            }
        }
        return $this->item($checklist,new ChecklistTemplateTransformer(),201);
    }
}