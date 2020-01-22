<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function save(Request $request)
    {
        //Melakukan validasi data yang akan dikirim form inputan
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        try {
            //Menyimpan data kedalam database
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            //Redirect kembali ke halaman product dengan flash message
            return redirect('/product')->with(['success' => '<strong>' . $product->title . '</strong> Telah disimpan']);

        } catch(\Exception $e) {
            //Apabila terdapat error maka redirect ke form input dan menampilkan flash message error
            return redirect('/product/new')->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id); //Query untuk mengambil data berdasarkan id
        //Kemudian mengupdate data tersebut
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);
        //Lalu diarahkan ke halaman product dengan flash message success
        return redirect('/product')->with(['success' => '<strong>' . $product->title . '</strong> Berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        $product = Product::find($id);//Query kedatabase untuk mengambil data berdasarkan id
        $product->delete();//Menghapus data yang ada di database
        return redirect('/product')->with(['success' => '<strong>' . $product->title . '</strong> Berhasil dihapus']);//Diarahkan kembali ke halaman product
    }
}
