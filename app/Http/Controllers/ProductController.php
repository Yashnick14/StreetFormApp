<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category'])->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sizes'       => 'required|array',
            'sizes.*'     => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|string',
            'image'       => 'nullable|image|max:4000',
            'image2'      => 'nullable|image|max:4000',
            'image3'      => 'nullable|image|max:4000',
            'image4'      => 'nullable|image|max:4000',
        ]);

        // Prevent duplicate product with same name + category
        $exists = Product::where('name', $validated['name'])
            ->where('category_id', $validated['category_id'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['Product with this name already exists in the selected category.']
            ]);
        }

        $validated['stockquantity'] = $validated['sizes'];
        unset($validated['sizes']);

        foreach (['image','image2','image3','image4'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('products', 'public');
            }
        }

        $product = Product::create($validated);

        return new ProductResource($product->load('category'));
    }

    public function show(string $id)
    {
        $product = Product::with(['category'])->findOrFail($id);
        return new ProductResource($product);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sizes'       => 'required|array',
            'sizes.*'     => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|string',
            'image'       => 'nullable|image|max:4000',
            'image2'      => 'nullable|image|max:4000',
            'image3'      => 'nullable|image|max:4000',
            'image4'      => 'nullable|image|max:4000',
        ]);

        // Prevent duplicate product with same name + category (ignore current product)
        $exists = Product::where('name', $validated['name'])
            ->where('category_id', $validated['category_id'])
            ->where('id', '!=', $product->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['Product with this name already exists in the selected category.']
            ]);
        }

        $validated['stockquantity'] = $validated['sizes'];
        unset($validated['sizes']);

        foreach (['image','image2','image3','image4'] as $field) {
            if ($request->hasFile($field)) {
                if ($product->$field) {
                    Storage::disk('public')->delete($product->$field);
                }
                $validated[$field] = $request->file($field)->store('products', 'public');
            }
        }

        $product->update($validated);

        return new ProductResource($product->load('category'));
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete images
        foreach (['image','image2','image3','image4'] as $field) {
            if ($product->$field) {
                Storage::disk('public')->delete($product->$field);
            }
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function view(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('customers.product-view', compact('product'));
    }

}
