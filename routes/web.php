<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Product;

// =====================================================================
// ADMIN (VUE) PAGES — EMPTY SEO
// =====================================================================
Route::get('/products', function () {
    $seo = [];
    return view('app', compact('seo'));
});

Route::get('/products/create', function () {
    $seo = [];
    return view('app', compact('seo'));
});

Route::get('/products/edit/{id}', function () {
    $seo = [];
    return view('app', compact('seo'));
});

// =====================================================================
// FORM SUBMIT ROUTES
// =====================================================================
Route::post('/products/store', [ProductController::class, 'store']);
Route::post('/products/update/{id}', [ProductController::class, 'update']);
Route::get('/products/delete/{id}', [ProductController::class, 'delete']);

// =====================================================================
// DATA ROUTES (JSON ONLY)
// =====================================================================
Route::get('/products-data', fn() => Product::all());
Route::get('/product-data/{id}', fn($id) => Product::find($id));

// =====================================================================
// CUSTOMER (VUE) PAGES — EMPTY SEO
// =====================================================================
Route::get('/customer/products', function () {
    $seo = [];
    return view('app', compact('seo'));
});

// =====================================================================
// CUSTOMER PRODUCT DETAILS (SEO INCLUDED)
// =====================================================================
Route::get('/customer/products/{id}', function ($id) {

    $product = Product::find($id);

    $seo = [
        'title' => $product->seo_meta_title,
        'description' => $product->seo_meta_description,
        'keywords' => $product->seo_meta_keywords,
        'canonical' => $product->seo_canonical,
        'og_title' => $product->og_meta_title,
        'og_description' => $product->og_meta_description,
        'og_image' => asset('product_images/' . $product->og_image),
    ];

    return view('app', compact('seo'));
});

// =====================================================================
// DEFAULT PAGE
// =====================================================================
Route::get('/', function () {
    return view('welcome');
});
