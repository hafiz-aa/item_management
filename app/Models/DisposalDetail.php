<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisposalDetail extends Model
{
    protected $table = 'dispossal_detail';

    protected $primaryKey = 'dispd_id';

    public $timestamps = false;

    protected $fillable = [
        'disph_id',
        'brokd_id',
        'itemd_id',
        'dispd_qty',
        'dispd_is_canceled',
        'dispd_canceled_reason',
        'dispd_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(DisposalHeader::class, 'disph_id', 'disph_id');
    }

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }
}
