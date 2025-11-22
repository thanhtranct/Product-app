<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,webp|max:2048', // max 2MB
        ]);

        try {
            // Xử lý upload ảnh
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // Gọi API ngoài (DummyJSON)
            $randomId = rand(1, 100);
            $externalApiData = null;
            
            try {
                $response = Http::get("https://dummyjson.com/products/{$randomId}");
                if ($response->successful()) {
                    $data = $response->json();
                    $externalApiData = $data['title'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error('API call failed: ' . $e->getMessage());
                // Không fail toàn bộ request nếu API lỗi
            }

            // Tạo sản phẩm mới
            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'external_api_data' => $externalApiData,
            ]);

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            // Xóa ảnh nếu có lỗi xảy ra
            if (isset($imagePath) && $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,webp|max:2048',
        ]);

        try {
            $oldImagePath = $product->image_path;
            $imagePath = $oldImagePath;

            // Xử lý upload ảnh mới
            if ($request->hasFile('image')) {
                // Lưu ảnh mới
                $imagePath = $request->file('image')->store('products', 'public');
                
                // Xóa ảnh cũ nếu có
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            // Cập nhật sản phẩm
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
            ]);

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            // Xóa ảnh mới nếu có lỗi và ảnh mới đã upload
            if (isset($imagePath) && $imagePath !== $oldImagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Xóa ảnh nếu có
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            // Xóa sản phẩm
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}