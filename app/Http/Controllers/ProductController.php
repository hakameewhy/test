<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('products', $imageName, 'public');

        Product::create([
            'image'         => $imageName,
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $products = Product::findOrFail($id);
        return view('products.show', compact('products'));
    }

    public function edit(string $id): View
    {
        $products = Product::findOrFail($id);
        return view('products.edit', compact('products'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        $products = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('products', $imageName, 'public');

            // Delete old image
            Storage::delete('public/products/' . $products->image);

            // Update product with new image
            $products->update([
                'image'         => $imageName,
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        } else {
            // Update without new image
            $products->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        }

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $products = Product::findOrFail($id);

        Storage::delete('public/products/' . $products->image);

        $products->delete();

        return redirect()->route('products.index')->with(['success'=>'Data berhasil dihapus']);
    }
}
