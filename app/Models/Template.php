<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model {
    use HasFactory;

    protected $table = 'template';
    protected $fillable = [
        'name','description',
        'due_interval','due_unit'
    ];

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('App\Models\TemplateItem');
    }
}