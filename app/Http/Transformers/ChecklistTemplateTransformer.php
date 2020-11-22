<?php

namespace App\Http\Transformers;

use App\Models\Checklist;

class ItemTemplateTransformer extends Transformer
{
    public $type = 'templates';

    public function transform($post)
    {
        return [
            'id'=>$post->id,
            'description'=>$post->description,
            'due_interval'=>$post->due_interval,
            'due_unit'=>$post->due_unit,
            'urgency'=>$post->urgency,
        ];
    }
}

class ChecklistTemplateTransformer  extends Transformer
{
    public $type = 'templates';

    protected $defaultIncludes = ['items',];

    public function includeItems(Checklist $checklist) {
        $items = $checklist->items;
        return $this->collection($items, new ItemTemplateTransformer);
    }

    public function transform($post)
    {
        return [
            'id'=>$post->id,
            'name'=>$post->template_name,
            'checklist'=>[
                'description'=>$post->description,
                'due_interval'=>$post->due_interval,
                'due_unit'=>$post->due_unit,
            ],
        ];
    }
}