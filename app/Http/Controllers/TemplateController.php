<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Template;
use App\Models\TemplateItem;
use App\Models\Checklist;
use App\Models\Item;
use App\Http\Resources\Template as TemplateResource;
use App\Http\Resources\TemplateSingle as TemplateSingleResource;
use App\Http\Transformers\TemplateTransformer;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        // $query = $request->query();
        $data = Template::with('items');
        return TemplateResource::collection($data->paginate());
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
        return $this->get_response(new TemplateSingleResource(Template::with('items')->find($template->id)),201);
    }

    public function show($templateid) {
        $template = Template::with('items')->find($templateid);
        if(!$template) {
            abort(404,'Not found.');
        }
        return $this->get_response(new TemplateSingleResource($template));
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

        return $this->get_response(new TemplateSingleResource(Template::with('items')->find($template->id)));
    }

    public function remove($templateid) {
        $template = Template::find($templateid);
        if(!$template) {
            abort(404,'Not Found.');
        }
        $template->delete();
        return $this->get_response(['result'=>'Template has been deleted'],204);
    }

    public function assign($templateid, Request $request) {
        $data = $request->json()->get();
        $validator = Validator::make(
            $data,
            [
                'data.*.attributes.object_id'=>'string|required',
                'data.*.attributes.object_domain'=>'string|required',
            ]
        );
        if($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }
        $template = Template::with('items')->find($templateid);
        if(!$template) {
            abort(404,'Not found.');
        }
        $now = CarbonImmutable::now();
        $due_date = $now->add($template->due_interval,$template->due_unit);
        foreach($data as $item) {
            $checklist = new Checklist();
            $checklist->object_domain = $item['attributes']['object_domain'];
            $checklist->object_id = $item['attributes']['object_id'];
            $checklist->description = $template->description;
            $checklist->due = $due_date;
            $checklist->save();
            foreach($template->items as $i) {                
                $checklist_item = new Item();
                $checklist_item->checklist_id = $checklist->id;
                $checklist_item->description = $i->description;
                $checklist_item->urgency = $i->urgency;
                if($i->due_interval&&$i->due_unit) {
                    $now = CarbonImmutable::now();
                    $due_date = $now->add($i->due_interval,$i->due_unit);
                    $checklist_item->due_date = $due_date;
                }
                $checklist_item->save();
            }
        }
        return $this->get_response(['result'=>'Assignment Success'],201);
    }
}