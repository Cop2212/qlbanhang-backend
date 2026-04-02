<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraderClick extends Model
{
    protected $table = 'trader_clicks';

    protected $fillable = [
        'trader_id',
        'product_id',
        'trader_link_id',
        'ref_code',
        'ip',
        'user_agent',
        'session_id',
        'utm_source',
        'utm_campaign',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function link()
    {
        return $this->belongsTo(TraderLink::class, 'trader_link_id');
    }
}
