<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\TemplateItem;

class TemplateSingle extends JsonResource
{
    public function toArray($request)
    {
        return [
            "data"=>[
                "type"=>"templates",
            "id"=>$this->id,
            "attributes"=>[
                "name"=>$this->name,
                'checklist'=>[
                    'description'=>$this->description,
                    'due_interval'=>$this->due_interval,
                    'due_unit'=>$this->due_unit,
                ],
                "items"=>TemplateItem::collection($this->whenLoaded('items'))
            ]
            ],
            "links"=>[
                "self"=>$request->url()
            ]
        ];
    }
}