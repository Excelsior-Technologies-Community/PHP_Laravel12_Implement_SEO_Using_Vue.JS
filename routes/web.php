<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =====================================================================
// SEO-CRAWLED CUSTOMER PAGES (SERVER-SIDE RENDERED META)
// =====================================================================
Route::get('/customer/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/customer/products/{id}', [ProductController::class, 'show'])->name('customer.product');

// Friendly slug-based URL (canonical target, SEO)
Route::get('/product/{slug}', [ProductController::class, 'showBySlug'])->name('product.show');

// =====================================================================
// DYNAMIC robots.txt
// =====================================================================
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /products/create\n";
    $content .= "Disallow: /products/edit\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

    return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
});

// =====================================================================
// SITEMAP
// =====================================================================
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// =====================================================================
// ADMIN (VUE) PAGES
// =====================================================================
Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
Route::get('/products/create', fn() => view('app', ['seo' => []]))->name('products.create');
Route::get('/products/edit/{id}', fn($id) => view('app', ['seo' => []]))->name('products.edit');

// =====================================================================
// FORM SUBMIT ROUTES
// =====================================================================
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');

// =====================================================================
// DATA ROUTES (JSON ONLY)
// =====================================================================
Route::get('/products-data', [ProductController::class, 'jsonIndex']);
Route::get('/product-data/{id}', [ProductController::class, 'jsonShow']);
