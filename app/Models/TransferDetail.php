<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    protected $table = 'transfer_detail';

    protected $primaryKey = 'ttd_id';

    public $timestamps = false;

    protected $fillable = [
        'tth_id',
        'itemd_id',
        'whsl_id_from',
        'ttd_status',
        'ttd_is_canceled',
        'ttd_canceled_reason',
        'ttd_notes',
    ];

    public function header()
    {
        return $this->belongsTo(TransferHeader::class, 'tth_id', 'tth_id');
    }

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'whsl_id_from', 'whsl_id');
    }
}
