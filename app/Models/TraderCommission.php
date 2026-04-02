<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderCommission extends Model
{
    use HasFactory;

    protected $table = 'trader_commissions';

    protected $fillable = [
        'trader_id',
        'consultation_id',
        'amount',
        'status',
        'paid_at',
        'note',
    ];

    // 🔗 Quan hệ với trader
    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
