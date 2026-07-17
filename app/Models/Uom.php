<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    protected $table = 'uom';

    protected $primaryKey = 'uom_id';

    public $timestamps = false;

    protected $fillable = [
        'uom_code',
        'uom_name',
    ];
}
