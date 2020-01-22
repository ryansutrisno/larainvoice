<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'DESC')->paginate(10);
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.add');
    }

    public function save(Request $request)
    {
        //Validasi data
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|max:13',//maksimum karakter 13 digit
            'address' => 'required|string',
            //unique berarti email ditable customers tidak boleh sama
            'email' => 'required|email|string|unique:customers,email' //format yg diterima harus email
        ]);

        try {
            $customers = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email
            ]);
            return redirect('/customer')->with(['success' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $customers = Customer::find($id);
        return view('customer.edit', compact('customers'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|max:13',
            'address' => 'required|string',
            'email' => 'required|email|string|exists:customers,email'
        ]);

        try {
            $customers = Customer::find($id);
            $customers->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address
            ]);
            return redirect('/customer')->with(['success' => 'Data berhasil diubah']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $customers = Customer::find($id);
        $customers->delete();
        return redirect()->back()->with(['success' => '<strong>' . $customers-name . '</strong> Berhasil dihapus']);
    }
}
