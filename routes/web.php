<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/debug-image/{id}', function($id) {
    $product = \App\Models\Product::findOrFail($id);
    return [
        'image_path' => $product->image_path,
        'file_exists' => Storage::disk('public')->exists($product->image_path),
        'full_path' => Storage::disk('public')->path($product->image_path),
        'url' => asset('storage/' . $product->image_path),
    ];
});

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');