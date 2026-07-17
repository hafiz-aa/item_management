<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $primaryKey = 'comp_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_code',
        'comp_name',
        'comp_address',
        'comp_logo',
    ];
}
