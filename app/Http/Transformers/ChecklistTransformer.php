<?php

namespace App\Http\Transformers;

class ChecklistTransformer  extends Transformer
{
    public $type = 'checklists';

    public function transform($post)
    {
        return [
            'id'=>$post->id,
            'object_domain'=>$post->object_domain,
            'object_id'=>$post->object_id,
            'task_id'=>$post->task_id,
            'description'=>$post->description,
            'is_completed'=>(bool)$post->is_completed,
            'due'=>$post->due,
            'urgency'=>$post->urgency,
            'completed_at'=>$post->completed_at,
            'updated_at'=>$post->updated_at,
            'created_at'=>$post->created_at,
            'updated_by'=>$post->updated_by,
            'created_by'=>$post->created_by,
            'links' => [
                'self' => '/checklist/'.$post->id,
            ],
        ];
    }
}