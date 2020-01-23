<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice_detail extends Model
{
    protected $guarded = [];

    // Define accessor
    public function getSubtotalAttribute()
    {
        // Nilai dari subtotal adalah qty * price
        return number_format($this->qty * $this->price);
    }

    // Define relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
