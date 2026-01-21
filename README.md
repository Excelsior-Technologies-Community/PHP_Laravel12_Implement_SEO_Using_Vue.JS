# PHP_Laravel12_Implement_SEO_Using_Vue.JS

# Step 1 : Install Laravel 12
```php
Composer create project-laravel/laravel your folder name
```
# Step 2 : Setup database for.env file
```php
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=your database name 
 DB_USERNAME=root
 DB_PASSWORD=
```
# Step 3 : Create SEO implement in Vue.js
# Create Migration File For Table Create 
```php
php artisan make:migration create_products_table
```
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('image')->nullable();
        $table->string('size');
        $table->decimal('price', 10, 2);
 // 🔹 SEO Image
        $table->string('seo_image')->nullable()->after('image');

        // 🔹 OG Tag Image
        $table->string('og_image')->nullable()->after('seo_image');

        // 🔹 SEO Meta Title
        $table->string('seo_meta_title')->nullable();

        // 🔹 OG Meta Title
        $table->string('og_meta_title')->nullable();

        // 🔹 SEO Meta Keywords
        $table->text('seo_meta_keywords')->nullable();

        // 🔹 OG Meta Keywords
        $table->text('og_meta_keywords')->nullable();

        // 🔹 SEO Meta Description
        $table->text('seo_meta_description')->nullable();

        // 🔹 OG Meta Description
        $table->text('og_meta_description')->nullable();

        // 🔹 SEO Canonical URL
        $table->string('seo_canonical')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```
# Step 4 : Create Product Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
protected $fillable = [
    'name', 'image', 'size', 'price',

    // SEO + OG
    'seo_image',
    'og_image',
    'seo_meta_title',
    'og_meta_title',
    'seo_meta_keywords',
    'og_meta_keywords',
    'seo_meta_description',
    'og_meta_description',
    'seo_canonical',
];
}
```
# Step 5 : Create Product Controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ---------------------------
    // STORE (CREATE PRODUCT)
    // ---------------------------
    public function store(Request $request)
    {
        // MAIN IMAGE
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_main.' . $request->image->extension();
            $request->image->move(public_path('product_images'), $imageName);
        }

        // SEO IMAGE
        $seoImage = null;
        if ($request->hasFile('seo_image')) {
            $seoImage = time() . '_seo.' . $request->seo_image->extension();
            $request->seo_image->move(public_path('product_images'), $seoImage);
        }

        // OG IMAGE
        $ogImage = null;
        if ($request->hasFile('og_image')) {
            $ogImage = time() . '_og.' . $request->og_image->extension();
            $request->og_image->move(public_path('product_images'), $ogImage);
        }

        // SAVE PRODUCT
        Product::create([
            'name'  => $request->name,
            'size'  => $request->size,
            'price' => $request->price,
            'image' => $imageName,

            // SEO / OG FIELDS
            'seo_image'            => $seoImage,
            'og_image'             => $ogImage,
            'seo_meta_title'       => $request->seo_meta_title,
            'og_meta_title'        => $request->og_meta_title,
            'seo_meta_keywords'    => $request->seo_meta_keywords,
            'og_meta_keywords'     => $request->og_meta_keywords,
            'seo_meta_description' => $request->seo_meta_description,
            'og_meta_description'  => $request->og_meta_description,
            'seo_canonical'        => $request->seo_canonical,
        ]);

        return redirect('/products');
    }

    // ---------------------------
    // UPDATE PRODUCT
    // ---------------------------
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        // MAIN IMAGE
        if ($request->hasFile('image')) {
            $imageName = time() . '_main.' . $request->image->extension();
            $request->image->move(public_path('product_images'), $imageName);
            $product->image = $imageName;
        }

        // SEO IMAGE
        if ($request->hasFile('seo_image')) {
            $seoImage = time() . '_seo.' . $request->seo_image->extension();
            $request->seo_image->move(public_path('product_images'), $seoImage);
            $product->seo_image = $seoImage;
        }

        // OG IMAGE
        if ($request->hasFile('og_image')) {
            $ogImage = time() . '_og.' . $request->og_image->extension();
            $request->og_image->move(public_path('product_images'), $ogImage);
            $product->og_image = $ogImage;
        }

        // TEXT FIELDS UPDATE
        $product->name  = $request->name;
        $product->size  = $request->size;
        $product->price = $request->price;

        // SEO FIELDS UPDATE
        $product->seo_meta_title       = $request->seo_meta_title;
        $product->og_meta_title        = $request->og_meta_title;
        $product->seo_meta_keywords    = $request->seo_meta_keywords;
        $product->og_meta_keywords     = $request->og_meta_keywords;
        $product->seo_meta_description = $request->seo_meta_description;
        $product->og_meta_description  = $request->og_meta_description;
        $product->seo_canonical        = $request->seo_canonical;

        // SAVE
        $product->save();

        return redirect('/products');
    }

    // ---------------------------
    // DELETE PRODUCT
    // ---------------------------
    public function delete($id)
    {
        Product::find($id)->delete();
        return redirect('/products');
    }
}
```
# Step 6 : Create Web Route for web.php file
```php
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
```
# Now Install Vue.js Packages in terminal
```php
npm install vue@3
```
```php
npm install vue-router@4
```
# Update  ViteConfig.js File
```php
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),   // VERY IMPORTANT
    ],
});
```
# Step 7 : Create Router.js file and app.js file and App.vue file in resource/js folder
# Router.js
```php
import { createRouter, createWebHistory } from 'vue-router';

// ADMIN PAGES
import ProductIndex from './pages/ProductIndex.vue'
import ProductCreate from './pages/ProductCreate.vue'
import ProductEdit from './pages/ProductEdit.vue'

// CUSTOMER PAGES
import CustomerIndex from './pages/customer/CustomerIndex.vue'
import CustomerDetails from './pages/customer/CustomerDetails.vue'

const routes = [
    // -----------------------------------
    // ADMIN ROUTES
    // -----------------------------------
    { path: '/products', component: ProductIndex },
    { path: '/products/create', component: ProductCreate },
    { path: '/products/edit/:id', component: ProductEdit, props: true },

    // -----------------------------------
    // CUSTOMER ROUTES
    // -----------------------------------
    { path: '/customer/products', component: CustomerIndex },
    { path: '/customer/products/:id', component: CustomerDetails, props: true },
];

export default createRouter({
    history: createWebHistory(),
    routes
});
```
# App.js
```php
import { createApp } from 'vue';
import router from './router';

import App from './App.vue';

createApp(App)
    .use(router)
    .mount('#app');
```
# App.vue
```php
<template>
   <router-view></router-view>
</template>
```
# Step 8 : Create productindex.vue , productcreate.vue,productedit.vue file create in resource/js/pages folder and also create customerindex.vue and customerdetails.vue file in resource/js/pages/customer folder
```php
resource/js/pages/productindex.vue
```
```php
resource/js/pages/productcreate.vue
```
```php
resource/js/pages/productedit.vue
```
```php
resource/js/pages/customer/customerindex.vue
```
```php
resource/js/pages/customer/customerdetails.vue
```
# Step 9 : Now Run Server for terminal
#  Open Terminal 1 
```php
php artisan serve
```
# Open Terminal 2  
```php
npm run dev
```
# http://localhost:8000/products
<img width="1631" height="556" alt="image" src="https://github.com/user-attachments/assets/7068ad36-44b1-425b-a6b5-20c8abc9ede6" />


 <img width="1121" height="319" alt="image" src="https://github.com/user-attachments/assets/1a9ffc2b-7cde-484e-a6a3-f45785ad0f15" />


# http://127.0.0.1:8000/customer/products
<img width="1679" height="721" alt="image" src="https://github.com/user-attachments/assets/986b2371-57fe-4f9d-a973-e1b0e04efa44" />


 
# Now Click Any One products view button then show product details  and after right click and select  view page source then  selected  product  to show SEO and OG details in view page sorce..
<img width="1393" height="593" alt="image" src="https://github.com/user-attachments/assets/7885afa3-8f8f-4e63-b22c-725646da60b4" />



