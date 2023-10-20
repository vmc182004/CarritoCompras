<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function shop()
    {
        $products = Product::all();
        return view('shop', ['title' => 'E-COMMERCE STORE | SHOP', 'products' => $products]);
    }

    public function cart()
    {
        $cartCollection = \Cart::getContent();
        return view('cart', ['title' => 'E-COMMERCE STORE | CART', 'cartCollection' => $cartCollection]);
    }

    public function remove(Request $request)
    {
        \Cart::remove($request->id);
        return redirect()->route('cart.index')->with('success_msg', 'Item is removed!');
    }

    public function add(Request $request)
    {
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => [
                'image' => $request->img,
                'slug' => $request->slug
            ]
        ]);
        return redirect()->route('cart.index')->with('success_msg', 'Item Agregado a sú Carrito!');
    }

   
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer', // La cantidad debe ser un número entero
        ]);
    
        $newQuantity = max(1, $request->quantity); // Asegura que la cantidad sea al menos 1
    
        \Cart::update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $newQuantity
            ]
        ]);
    
        return redirect()->route('cart.index')->with('success_msg', 'El carrito se ha actualizado correctamente.');
    }
    


    public function clear()
    {
        \Cart::clear();
        return redirect()->route('cart.index')->with('success_msg', 'Car is cleared!');
    }
}
