<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    public $type = 'unknown';
    
    public abstract function transform($post);
}