<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json(Product::latest()->get());
        }

        $seoService = app(\App\Services\SeoService::class);
        $seo = $seoService->forPage(
            config('seo.site_name', 'Laravel'),
            config('seo.defaults.title', 'Online Shop'),
            config('seo.defaults.description', 'Best products online at the best prices.'),
            request()->fullUrl()
        );

        return view('app', [
            'seo' => $seo,
            'structuredData' => $this->siteStructuredData(),
        ]);
    }

    public function showBySlug(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return $this->show($request, $product->id);
    }

    public function jsonIndex()
    {
        return response()->json(Product::all());
    }

    public function jsonShow($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($product);
        }

        $seoService = app(\App\Services\SeoService::class);
        $meta = $seoService->forProduct($product, $request->fullUrl());

        return view('app', [
            'seo' => $meta,
            'structuredData' => $this->structuredData($product),
            'product' => $product,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $this->handleUpload($request, 'image', $data['image'] ?? null);
        $data['seo_image'] = $this->handleUpload($request, 'seo_image', $request->input('seo_image'));
        $data['og_image'] = $this->handleUpload($request, 'og_image', $request->input('og_image'));

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect('/products')->with('success', 'Product created successfully.');
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        $data['image'] = $this->handleUpload($request, 'image', $request->input('image'));
        $data['seo_image'] = $this->handleUpload($request, 'seo_image', $request->input('seo_image'));
        $data['og_image'] = $this->handleUpload($request, 'og_image', $request->input('og_image'));

        $product->fill($data)->save();

        if ($request->wantsJson()) {
            return response()->json($product);
        }

        return redirect('/products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->deleteImages($product);
        $product->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect('/products')->with('success', 'Product deleted successfully.');
    }

    protected function handleUpload(Request $request, string $field, $existing): ?string
    {
        if ($request->hasFile($field)) {
            $this->deleteFile($existing);

            $file = $request->file($field);
            $name = time() . '_' . Str::random(6) . '_' . $field . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('product_images'), $name);

            return $name;
        }

        return $existing;
    }

    protected function deleteFile($filename): void
    {
        if ($filename) {
            $path = public_path('product_images') . '/' . $filename;
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    protected function deleteImages(Product $product): void
    {
        foreach (['image', 'seo_image', 'og_image'] as $field) {
            $this->deleteFile($product->{$field});
        }
    }

    protected function structuredData(Product $product): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $product->image_url,
            'description' => $product->seo_meta_description,
            'sku' => 'PROD-' . $product->id,
            'offers' => [
                '@type' => 'Offer',
                'url' => $product->product_url,
                'priceCurrency' => 'INR',
                'price' => $product->price,
                'availability' => 'https://schema.org/InStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('seo.site_name'),
                ],
            ],
        ];
    }

    protected function siteStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'WebSite',
            'name' => config('seo.site_name'),
            'url' => config('app.url', env('APP_URL', 'http://localhost')),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url', env('APP_URL', 'http://localhost')) . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}
