<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraderLink extends Model
{
    protected $table = 'trader_links';

    protected $fillable = [
        'trader_id',
        'product_id',
        'code',
        'campaign',
        'platform',
    ];

    // 🔗 Quan hệ
    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
