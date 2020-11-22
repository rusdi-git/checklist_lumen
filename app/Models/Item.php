<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model {
    use HasFactory;

    protected $table = 'item';
    protected $fillable = [
        'created_by','updated_by','due','urgency',
        'description','is_completed','completed_at',
        'checklist_id','task_id','assignee_id'
    ];

    public function checklist()
    {
        return $this->belongsTo('App\Models\Checklist');
    }
}