<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateItem extends Model {
    use HasFactory;

    protected $table = 'template_item';
    protected $fillable = [
        'description','due_interval','due_unit',
        'template_id','urgency'
    ];

    public $timestamps = false;

    public function template()
    {
        return $this->belongsTo('App\Models\Template');
    }
}