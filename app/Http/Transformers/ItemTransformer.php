<?php

namespace App\Http\Transformers;

class ItemTransformer  extends Transformer
{
    public $type = 'items';

    public function transform($post)
    {
        return [
            'id'=>$post->id,
            'task_id'=>$post->task_id,
            'description'=>$post->description,
            'is_completed'=>(bool)$post->is_completed,
            'completed_at'=>$post->completed_at,
            'due'=>$post->due,
            'urgency'=>$post->urgency,            
            'updated_at'=>$post->updated_at,
            'created_at'=>$post->created_at,
            'updated_by'=>$post->updated_by,
            'created_by'=>$post->created_by,
            'assignee_id'=>$post->assignee_id,
        ];
    }
}