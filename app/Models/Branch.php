<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'branch_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $table = 'branches';

    protected $fillable = [
        'comp_id',
        'branch_id',
        'branch_code',
        'branch_name',
        'branch_is_headquarter',
        'branch_address',
    ];

    protected function casts(): array
    {
        return [
            'branch_is_headquarter' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'branch_id', 'branch_id');
    }
}
