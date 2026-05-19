<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'user_warehouse');
    }

    public function createdItems()
    {
        return $this->hasMany(Item::class, 'created_by');
    }

    public function updatedItems()
    {
        return $this->hasMany(Item::class, 'updated_by');
    }

    public function createdWarehouses()
    {
        return $this->hasMany(Warehouse::class, 'created_by');
    }

    public function updatedWarehouses()
    {
        return $this->hasMany(Warehouse::class, 'updated_by');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
