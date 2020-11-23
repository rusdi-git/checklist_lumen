<?php

namespace App\Http\Transformers;

use App\Models\Template;


class TemplateTransformer  extends Transformer
{
    public $type = 'templates';

    public function includeItems(Template $template) {
        $items = $template->items;
        $result = [];
        foreach($items as $item) {
            $result[] = [
                'description'=>$item->description,
                'urgency'=>$item->urgency,
                'due_interval'=>$item->due_interval,
                'due_unit'=>$item->due_unit
            ];
        }
        return $result;
    }

    public function transform($post)
    {
        return [
            'id'=>$post->id,
            'name'=>$post->name,
            'checklist'=>[
                'description'=>$post->description,
                'due_interval'=>$post->due_interval,
                'due_unit'=>$post->due_unit,
            ],
            'items'=>$this->includeItems($post)
        ];
    }
}