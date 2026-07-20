<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branch';

    protected $primaryKey = 'branch_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'branch_code',
        'branch_name',
        'branch_is_headquarter',
        'branch_address',
    ];

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'branch_id', 'branch_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_branch', 'branch_id', 'users_id', 'branch_id', 'users_id');
    }
}
