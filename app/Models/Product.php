<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'category_id',
        'brand_id',
        'thumbnail',
        'thumbnail_public_id',
        'price',
        'sale_price',
        'stock',
        'color',
        'is_featured',
        'is_active'
    ];

    // Quan hệ Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
