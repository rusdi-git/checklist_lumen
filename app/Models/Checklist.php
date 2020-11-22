<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checklist extends Model {
    use HasFactory;

    protected $table = 'checklist';
    protected $fillable = [
        'created_by','updated_by','object_domain',
        'object_id','due','urgency','description',
        'is_completed','completed_at','task_id',
        'is_template'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}