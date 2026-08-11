<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangeItemDescriptionHeader extends Model
{
    protected $table = 'change_item_description_header';

    protected $primaryKey = 'cidh_id';

    public $timestamps = false;

    protected $fillable = [
        'comp_id',
        'branch_id',
        'cidh_code',
        'cidh_date',
        'cidh_is_canceled',
        'cidh_canceled_date',
        'cidh_canceled_reason',
        'canceled_by',
        'cidh_notes',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'cidh_date' => 'date',
        'cidh_canceled_date' => 'date',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ChangeItemDescriptionDetail::class, 'cidh_id', 'cidh_id');
    }
}
