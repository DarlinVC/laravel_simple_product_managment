<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products
    public function index()
    {
        $products = Product::with('currency', 'prices.currency')->get();
        return response()->json($products);
    }

    // POST /products
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'                => 'required|string|max:255',
                'description'         => 'nullable|string',
                'price'               => 'required|numeric',
                'currency_id'         => 'required|exists:currencies,id',
                'tax_cost'            => 'required|numeric',
                'manufacturing_cost'  => 'required|numeric',
            ]);

            $product = Product::create($validated);
            return response()->json($product, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('currency_id')) {
                return response()->json(['msg' => 'Moneda no encontrada'], 422);
            }
            return response()->json($errors, 422);
        }
    }

    // GET /products/{id}
    public function show($id)
    {
        $product = Product::with('currency', 'prices.currency')->findOrFail($id);
        return response()->json($product);
    }

    // PUT /products/{id}
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name'                => 'sometimes|required|string|max:255',
                'description'         => 'nullable|string',
                'price'               => 'sometimes|required|numeric',
                'currency_id'         => 'sometimes|required|exists:currencies,id',
                'tax_cost'            => 'sometimes|required|numeric',
                'manufacturing_cost'  => 'sometimes|required|numeric',
            ]);

            $product = Product::findOrFail($id);
            $product->update($validated);
            return response()->json($product);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('currency_id')) {
                return response()->json(['msg' => 'Moneda no encontrada'], 422);
            }
            return response()->json($errors, 422);
        }
    }

    // DELETE /products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
