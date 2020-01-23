<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    //Define Accessor
    public function getTaxAttribute()
    {
        //Mendapatkan tax 2% dari total harga
        return ($this->total * 2) / 100;
    }

    public function getTotalPriceAttribute()
    {
        // Mendapatkan total harga baru yang telah dijumlahkan dengan tax
        return ($this->total + (($this->total * 2) / 100));
    }

    // Define relationships
    public function customer()
    {
        //Invoice reference ke tabel customers
        return $this->belongsTo(Customer::class);
    }

    public function detail()
    {
        //Invoice memiliki hubungan hasMany ke table invoice_detail
        return $this->hasMany(Invoice_detail::class);
    }
}
