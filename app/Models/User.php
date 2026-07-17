<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, SoftDeletes;

    protected $table = 'users';

    protected $primaryKey = 'users_id';

    protected $fillable = [
        'users_code',
        'users_name',
        'users_names',
        'users_email',
        'users_phones',
        'users_password',
        'users_hint',
        'users_hint_answer',
        'users_status',
        'users_level',
        'email',
        'password',
        'email_verified_at',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'users_id';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['users_name'] ?? $this->attributes['name'] ?? '';
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'users_branch', 'users_id', 'branch_id', 'users_id', 'branch_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'users_branch', 'users_id', 'branch_id', 'users_id', 'branch_id')
            ->select('warehouse_location.*')
            ->distinct();
    }
}
