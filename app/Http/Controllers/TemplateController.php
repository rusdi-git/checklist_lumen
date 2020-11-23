<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Template;
use App\Models\TemplateItem;
use App\Http\Transformers\TemplateTransformer;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data = Template::all();
        return $this->collection($data,new TemplateTransformer());
    }

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
        $template = new Template();
        $template->name = $data['name'];
        $template->description = $data['checklist']['description'];
        $template->due_interval = $data['checklist']['due_interval'];
        $template->due_unit = $data['checklist']['due_unit'];
        $template->save();

        if(!empty($data['items'])) {
            $inserted_item = [];
            foreach($data['items'] as $item) {
                $inserted_item[] = [
                    'template_id'=>$template->id,
                    'description'=>$item['description'],
                    'urgency'=>$item['urgency'],
                    'due_interval'=>$item['due_interval'],
                    'due_unit'=>$item['due_unit']
                ];
            }
            TemplateItem::insert($inserted_item);
        }
        return $this->item($template,new TemplateTransformer(),201);
    }

    public function show($templateid) {
        $template = Template::find($templateid);
        if(!$template) {
            abort(404,'Not found.');
        }
        return $this->item($template,new TemplateTransformer());
    }

    public function edit($templateid, Request $request) {
        $template = Template::find($templateid);
        if(!$template) {
            abort(404,'Not Found.');
        }
        $data = $request->json()->get('data')['attributes'];
        $validator = Validator::make(
            $data,
            [
                'name'=>'string',
                'checklist.description'=>'string',
                'checklist.due_interval'=>'nullable|integer',
                'checklist.due_unit'=>[
                    'nullable','string',Rule::in(['minute','hour','day','week','month'])
                ],
                'items.*.description'=>'string',
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
        
        $fields = ['name','description','due_interval','due_unit'];
        foreach($data as $key=>$value) {
            if(in_array($key,$fields)) {
                $template->$key = $value;
            }
        }
        foreach($data['checklist'] as $key=>$value) {
            if(in_array($key,$fields)) {
                $template->$key = $value;
            }
        }
        $template->update();

        return $this->item($template,new TemplateTransformer());
    }

    public function remove($templateid) {
        $template = Template::find($templateid);
        if(!$template) {
            abort(404,'Not Found.');
        }
        $template->delete();
        return $this->get_response(['result'=>'Template has been deleted'],204);
    }
}