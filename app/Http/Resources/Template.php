<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\TemplateItem;

class Template extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name"=>$this->name,
            'checklist'=>[
                'description'=>$this->description,
                'due_interval'=>$this->due_interval,
                'due_unit'=>$this->due_unit,
            ],
            "items"=>TemplateItem::collection($this->whenLoaded('items'))
        ];
    }
}