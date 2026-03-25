<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderProfile extends Model
{
    use HasFactory;

    protected $table = 'trader_profiles';

    protected $fillable = [
        'trader_id',
        'bank_name',
        'bank_number',
        'bank_owner',
        'phone',
        'note',
        'status',
    ];

    // 🔗 Quan hệ với trader
    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }
}
