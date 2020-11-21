<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model {
    protected $table = 'checklist';
    protected $fillable = [
        'created_by','updated_by','object_domain',
        'object_id','due','urgency','description',
        'is_completed','completed_at','task_id',
        'is_template'
    ];
    protected $dates = ['due','completed_at'];
}