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
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Quan hệ Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class)
            ->withDefault([
                'name' => 'Không có hãng'
            ]);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
}
