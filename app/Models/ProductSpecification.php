<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'template_id',
        'name',
        'value',
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function template()
    {
        return $this->belongsTo(SpecificationTemplate::class, 'template_id');
    }
}
