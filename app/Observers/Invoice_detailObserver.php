<?php

namespace App\Observers;

use App\Invoice_detail;
use App\Invoice;

class Invoice_detailObserver
{
    // Karena fungsi yang dijalankan sama, maka kita membuatnya kedalam function baru
    private function generateTotal($invoiceDetail)
    {
        // Mengambil invoice_id
        $invoice_id = $invoiceDetail->invoice_id;
        // Select dari tabel invoice_details berdasarkan invoice
        $invoice_detail = Invoice_detail::where('invoice_id', $invoice_id)->get();
        // Kemudian dijumlah untuk mendapatkan totalnya
        $total = $invoice_detail->sum(function($i) {
            // Dimana ketentuan yang dijumlahkan adalah hasil dari price * qty
            return $i->price * $i->qty;
        });
        // Update tabel invoice pada field total
        $invoiceDetail->invoice()->update([
            'total' => $total
        ]);
    }
    /**
     * Handle the invoice_detail "created" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function created(Invoice_detail $invoiceDetail)
    {
        //Panggil method baru tersebut
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "updated" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function updated(Invoice_detail $invoiceDetail)
    {
        //Panggil method baru tersebut
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "deleted" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function deleted(Invoice_detail $invoiceDetail)
    {
        //Panggil method baru tersebut
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "restored" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function restored(Invoice_detail $invoiceDetail)
    {
        //
    }

    /**
     * Handle the invoice_detail "force deleted" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function forceDeleted(Invoice_detail $invoiceDetail)
    {
        //
    }

}
