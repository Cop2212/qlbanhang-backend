<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class, 'template_id');
    }
}
