<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WriteOffDetail extends Model
{
    protected $table = 'write_off_detail';

    protected $primaryKey = 'wod_id';

    public $timestamps = false;

    protected $fillable = [
        'woh_id',
        'brokd_id',
        'itemd_id',
        'wod_qty',
        'wod_is_canceled',
        'wod_canceled_reason',
        'wod_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(WriteOffHeader::class, 'woh_id', 'woh_id');
    }

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }
}
