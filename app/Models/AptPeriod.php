<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AptPeriod extends Model
{
    protected $table = 'apt_period';

    protected $primaryKey = 'aptp_id';

    public $timestamps = false;

    protected $fillable = [
        'aptp_month_1',
        'aptp_month_2',
        'aptp_year',
        'aptp_is_active_period',
        'aptp_is_closed',
    ];
}
