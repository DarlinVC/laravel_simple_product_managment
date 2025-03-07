<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    // GET /products/{id}/prices
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $prices = $product->prices()->with('currency')->get();
        return response()->json($prices);
    }

    // POST /products/{id}/prices
    public function store(Request $request, $id)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'price'       => 'required|numeric',
        ]);

        // Verificar que el producto exista
        $product = Product::findOrFail($id);

        $priceData = [
            'product_id'  => $product->id,
            'currency_id' => $request->input('currency_id'),
            'price'       => $request->input('price'),
        ];

        $productPrice = ProductPrice::create($priceData);
        return response()->json($productPrice, 201);
    }
}
