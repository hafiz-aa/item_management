<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $primaryKey = 'cust_id';

    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'comp_id',
        'cust_code',
        'cust_name',
        'cust_npwp',
        'cust_address',
        'cust_status',
        'cust_phone',
        'cust_fax',
        'cust_web_address',
        'cust_email',
        'custtp_id',
        'cust_type',
    ];

    public function isActive(): bool
    {
        return $this->cust_status === '0';
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'custtp_id', 'custtp_id');
    }
}
