<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'image',
        'image_public_id',
        'link',
        'sort_order',
        'is_active'
    ];
}
