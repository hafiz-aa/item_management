<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable, SoftDeletes;

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

    public function createdItemHeaders()
    {
        return $this->hasMany(ItemHeader::class, 'created_by');
    }

    public function updatedItemHeaders()
    {
        return $this->hasMany(ItemHeader::class, 'updated_by');
    }

    public function createdItemDetails()
    {
        return $this->hasMany(ItemDetail::class, 'created_by');
    }

    public function updatedItemDetails()
    {
        return $this->hasMany(ItemDetail::class, 'updated_by');
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
