<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptTransferDetail extends Model
{
    protected $table = 'receipt_transfer_detail';

    protected $primaryKey = 'rtd_id';

    public $timestamps = false;

    protected $fillable = [
        'rth_id',
        'ttd_id',
        'itemd_id',
        'rtd_qty',
        'whsl_id_old',
        'whsl_id_new',
        'rtd_is_canceled',
        'rtd_canceled_reason',
        'rtd_notes',
    ];

    public function header()
    {
        return $this->belongsTo(ReceiptTransferHeader::class, 'rth_id', 'rth_id');
    }

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class, 'itemd_id', 'itemd_id');
    }

    public function warehouseNew()
    {
        return $this->belongsTo(Warehouse::class, 'whsl_id_new', 'whsl_id');
    }
}
