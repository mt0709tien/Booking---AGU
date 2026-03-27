<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'invoice_id',
        'ten_dich_vu',
        'so_luong',
        'don_gia'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}