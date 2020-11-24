<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateItem extends JsonResource
{
    public function toArray($request)
    {
        return [
            'description'=>$this->description,
            'due_interval'=>$this->due_interval,
            'due_unit'=>$this->due_unit,
            'urgency'=>$this->urgency
        ];
    }
}