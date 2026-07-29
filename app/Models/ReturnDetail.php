<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnDetail extends Model
{
    protected $table = 'return_detail';

    protected $primaryKey = 'retd_id';

    public $timestamps = false;

    protected $fillable = [
        'reth_id',
        'issuingd_id',
        'retd_qty',
        'whsl_id_first',
        'whsl_id',
        'retd_is_canceled',
        'retd_canceled_reason',
        'retd_notes',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(ReturnHeader::class, 'reth_id', 'reth_id');
    }
}
