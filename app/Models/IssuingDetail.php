<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuingDetail extends Model
{
    protected $table = 'issuing_detail';

    protected $primaryKey = 'issuingd_id';

    public $timestamps = false;

    protected $fillable = [
        'issuingh_id',
        'itemd_id',
        'issuingd_qty',
        'issuingd_status',
        'issuingd_is_canceled',
        'issuingd_canceled_reason',
        'issuingd_is_return',
        'issuingd_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(IssuingHeader::class, 'issuingh_id', 'issuingh_id');
    }

    public function itemDetail(): BelongsTo
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }
}
