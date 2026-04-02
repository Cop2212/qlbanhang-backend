<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TraderProfile;
use App\Models\TraderCommission;

class Trader extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ref_code',
        'refresh_token',
        'refresh_token_expired_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(TraderProfile::class);
    }

    public function commissions()
    {
        return $this->hasMany(TraderCommission::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function links()
    {
        return $this->hasMany(TraderLink::class);
    }
}
