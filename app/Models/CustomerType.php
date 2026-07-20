<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    protected $table = 'customer_type';

    protected $primaryKey = 'custtp_id';

    public $timestamps = false;

    protected $fillable = [
        'custtp_code',
        'custtp_name',
        'custtp_is_active',
    ];

    public function isActive(): bool
    {
        return $this->custtp_is_active === '0';
    }
}
