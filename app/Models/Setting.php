<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'logo_public_id',
        'email',
        'phone',
        'address',
        'facebook',
        'youtube',
        'zalo',
        'footer_text',
        'max_sliders',
        'zalo_oa_id',
        'messenger_url'
    ];
}
