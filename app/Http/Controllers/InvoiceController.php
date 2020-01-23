<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use App\Invoice_detail;
use App\Product;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoice = Invoice::with(['customer', 'detail'])->orderBy('created_at', 'DESC')->paginate(10);
        return view('invoice.index', compact('invoice'));
    }
    public function create()
    {
        $customers = Customer::orderBy('created_at', 'DESC')->get();
        return view('invoice.create', compact('customers'));
    }

    public function save(Request $request)
    {
        //Validasi
        $this->validate($request, [
            'customer_id' => 'required|exists:customers,id'
        ]);

        try {
            //Menyimpan data ke table invoices
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'total' => 0
            ]);
            //Rediret ke route invoice.edit dengan mengirimkan parameter id
            return redirect(route('invoice.edit', ['id' => $invoice->id]));
        } catch (\Exception $e) {
            //Jika gagal redirect back ke form, dan menampilkan error message
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit ($id)
    {
        $invoice = Invoice::with(['customer', 'detail', 'detail.product'])->find($id);
        $products = Product::orderBy('title', 'ASC')->get();
        return view('invoice.edit', compact('invoice', 'products'));
    }

    public function update(Request $request, $id)
    {
        //Validasi
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

        try {
            //Select dari tabel invoices berdasarkan id
            $invoice = Invoice::find($id);
            //Select dari tabel products berdasarkan id
            $products = Product::find($request->product_id);
            //Select dari tabel invoice_details berdasarkan product_id & invoice_id
            $invoice_detail = $invoice->detail()->where('product_id', $products->id)->first();

            //Jika datanya ada
            if ($invoice_detail) {
                //Maka data tersebut di update qty nya
                $invoice_detail->update([
                    'qty' => $invoice_detail->qty + $request->qty
                ]);
            } else {
                //Jika tidak maka ditambahkan record baru
                Invoice_detail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $request->product_id,
                    'price' => $products->price,
                    'qty' => $request->qty
                ]);
            }

            //Kemudian di redirect kembali ke form yang sama
            return redirect()->back()->with(['success' => 'Produk telah ditambahkan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return redirect()->back()->with(['success' => 'Data berhasil dihapus']);
    }

    public function deleteProduct($id)
    {
        //Select dari tabel invoice_details berdasarkan id
        $detail = Invoice_detail::find($id);
        //Kemudian dihapus
        $detail->delete();
        //Dan di redirect kembali
        return redirect()->back()->with(['success' => 'Produk telah dihapus']);
    }

    public function generateInvoice($id)
    {
        //Get data berdasarkan id
        $invoice = Invoice::with(['customer', 'detail', 'detail.product'])->find($id);
        // Load PDF yang merujuk ke view print.blade.php dengan mengirimkan data dari invoice
        // Kemudian menggunakan pengaturan landscape A4
        $pdf = PDF::loadView('invoice.print', compact('invoice'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
