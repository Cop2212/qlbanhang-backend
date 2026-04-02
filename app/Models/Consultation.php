<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'product_id',
        'trader_id',
        'ref_code',
        'status',
        'result',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'trader_link_id', 
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function commission()
    {
        return $this->hasOne(TraderCommission::class);
    }

    public function link()
    {
        return $this->belongsTo(TraderLink::class, 'trader_link_id');
    }
}
