<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN / PRODUCT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->productQuery($request)->get();
        }

        $seoService = app(\App\Services\SeoService::class);

        $seo = $seoService->forPage(
            config('seo.site_name', 'Laravel'),
            config('seo.defaults.title', 'Online Shop'),
            config(
                'seo.defaults.description',
                'Best products online at the best prices.'
            ),
            $request->fullUrl()
        );

        return view('app', [
            'seo' => $seo,

            'structuredData' => [
                $this->siteStructuredData(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT QUERY
    |--------------------------------------------------------------------------
    |
    | New features:
    | - Search
    | - Sorting
    | - Price filter
    | - SEO score filter
    | - Pagination
    |
    */

    protected function productQuery(Request $request)
    {
        $query = Product::query();

        /*
        | Search
        */
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%");
            });
        }

        /*
        | Minimum price
        */
        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                (float) $request->input('min_price')
            );
        }

        /*
        | Maximum price
        */
        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                (float) $request->input('max_price')
            );
        }

        /*
        | SEO STATUS FILTER
        |
        | Excellent = 90+
        | Good = 70-89
        | Needs Improvement = below 70
        |
        */

        if ($request->filled('seo_status')) {
            $status = $request->input('seo_status');

            if ($status === 'excellent') {
                $query->whereRaw(
                    '(CASE
                        WHEN seo_meta_title IS NOT NULL
                        AND seo_meta_title != ""
                        AND CHAR_LENGTH(seo_meta_title) BETWEEN 50 AND 60
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_description IS NOT NULL
                        AND seo_meta_description != ""
                        AND CHAR_LENGTH(seo_meta_description) BETWEEN 120 AND 160
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_keywords IS NOT NULL
                        AND seo_meta_keywords != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_canonical IS NOT NULL
                        AND seo_canonical != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN slug IS NOT NULL
                        AND slug != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN alt_text IS NOT NULL
                        AND alt_text != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_title IS NOT NULL
                        AND og_meta_title != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_description IS NOT NULL
                        AND og_meta_description != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_image IS NOT NULL
                        AND og_image != ""
                        THEN 10 ELSE 0 END
                    ) >= 9'
                );
            }

            if ($status === 'good') {
                $query->whereRaw(
                    '(CASE
                        WHEN seo_meta_title IS NOT NULL
                        AND seo_meta_title != ""
                        AND CHAR_LENGTH(seo_meta_title) BETWEEN 50 AND 60
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_description IS NOT NULL
                        AND seo_meta_description != ""
                        AND CHAR_LENGTH(seo_meta_description) BETWEEN 120 AND 160
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_keywords IS NOT NULL
                        AND seo_meta_keywords != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_canonical IS NOT NULL
                        AND seo_canonical != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN slug IS NOT NULL
                        AND slug != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN alt_text IS NOT NULL
                        AND alt_text != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_title IS NOT NULL
                        AND og_meta_title != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_description IS NOT NULL
                        AND og_meta_description != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_image IS NOT NULL
                        AND og_image != ""
                        THEN 10 ELSE 0 END
                    ) BETWEEN 7 AND 8'
                );
            }

            if ($status === 'needs_improvement') {
                $query->whereRaw(
                    '(CASE
                        WHEN seo_meta_title IS NOT NULL
                        AND seo_meta_title != ""
                        AND CHAR_LENGTH(seo_meta_title) BETWEEN 50 AND 60
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_description IS NOT NULL
                        AND seo_meta_description != ""
                        AND CHAR_LENGTH(seo_meta_description) BETWEEN 120 AND 160
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_meta_keywords IS NOT NULL
                        AND seo_meta_keywords != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN seo_canonical IS NOT NULL
                        AND seo_canonical != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN slug IS NOT NULL
                        AND slug != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN alt_text IS NOT NULL
                        AND alt_text != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_title IS NOT NULL
                        AND og_meta_title != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_meta_description IS NOT NULL
                        AND og_meta_description != ""
                        THEN 10 ELSE 0 END
                    +
                    CASE
                        WHEN og_image IS NOT NULL
                        AND og_image != ""
                        THEN 10 ELSE 0 END
                    ) < 7'
                );
            }
        }

        /*
        | Sorting
        */

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = [
            'name',
            'price',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | JSON PRODUCT LIST
    |--------------------------------------------------------------------------
    */

    public function jsonIndex(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        if ($perPage < 1 || $perPage > 100) {
            $perPage = 5;
        }

        $products = $this->productQuery($request)
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($products);
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT STATISTICS
    |--------------------------------------------------------------------------
    */

    public function statistics()
    {
        $total = Product::count();

        $average = Product::avg('price');
        $highest = Product::max('price');
        $lowest = Product::min('price');

        return response()->json([
            'total_products' => $total,
            'average_price' => round((float) $average, 2),
            'highest_price' => (float) ($highest ?? 0),
            'lowest_price' => (float) ($lowest ?? 0),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PRODUCT BY SLUG
    |--------------------------------------------------------------------------
    */

    public function showBySlug(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return $this->show($request, $product->id);
    }

    /*
    |--------------------------------------------------------------------------
    | JSON PRODUCT
    |--------------------------------------------------------------------------
    */

    public function jsonShow($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function jsonShowBySlug($slug)
    {
        $product = Product::where(
            'slug',
            $slug
        )->firstOrFail();

        return response()->json($product);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PRODUCT
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($product);
        }

        $seoService = app(\App\Services\SeoService::class);

        $meta = $seoService->forProduct(
            $product,
            $request->fullUrl()
        );

        return view('app', [
            'seo' => $meta,

            'structuredData' => [
                $this->structuredData($product),
                $this->breadcrumbStructuredData($product),
            ],

            'product' => $product,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $this->handleUpload(
            $request,
            'image',
            $data['image'] ?? null
        );

        $data['seo_image'] = $this->handleUpload(
            $request,
            'seo_image',
            $request->input('seo_image')
        );

        $data['og_image'] = $this->handleUpload(
            $request,
            'og_image',
            $request->input('og_image')
        );

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect('/products')
            ->with('success', 'Product created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validated();

        $data['image'] = $this->handleUpload(
            $request,
            'image',
            $request->input('image')
        );

        $data['seo_image'] = $this->handleUpload(
            $request,
            'seo_image',
            $request->input('seo_image')
        );

        $data['og_image'] = $this->handleUpload(
            $request,
            'og_image',
            $request->input('og_image')
        );

        $product->fill($data)->save();

        if ($request->wantsJson()) {
            return response()->json($product);
        }

        return redirect('/products')
            ->with('success', 'Product updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->deleteImages($product);

        $product->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect('/products')
            ->with('success', 'Product deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOAD
    |--------------------------------------------------------------------------
    */

    protected function handleUpload(
        Request $request,
        string $field,
        $existing
    ): ?string {
        if ($request->hasFile($field)) {
            $this->deleteFile($existing);

            $file = $request->file($field);

            $name =
                time()
                . '_'
                . Str::random(6)
                . '_'
                . $field
                . '.'
                . $file->getClientOriginalExtension();

            $file->move(
                public_path('product_images'),
                $name
            );

            return $name;
        }

        return $existing;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE IMAGE
    |--------------------------------------------------------------------------
    */

    protected function deleteFile($filename): void
    {
        if ($filename) {
            $path =
                public_path('product_images')
                . '/'
                . $filename;

            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    protected function deleteImages(Product $product): void
    {
        foreach (
            [
                'image',
                'seo_image',
                'og_image',
            ] as $field
        ) {
            $this->deleteFile(
                $product->{$field}
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT JSON-LD
    |--------------------------------------------------------------------------
    */

    protected function structuredData(Product $product): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',

            'name' => $product->name,

            'image' => array_values(
                array_filter([
                    $product->image_url,
                    $product->seo_image_url,
                    $product->og_image_url,
                ])
            ),

            'description' =>
            $product->seo_meta_description
                ?: $product->name,

            'sku' => 'PROD-' . $product->id,

            'url' => $product->product_url,

            'offers' => [
                '@type' => 'Offer',

                'url' => $product->product_url,

                'priceCurrency' => 'INR',

                'price' => $product->price,

                'availability' =>
                'https://schema.org/InStock',

                'seller' => [
                    '@type' => 'Organization',

                    'name' =>
                    config('seo.site_name'),
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | WEBSITE JSON-LD
    |--------------------------------------------------------------------------
    */

    protected function siteStructuredData(): array
    {
        $baseUrl = config(
            'app.url',
            env(
                'APP_URL',
                'http://localhost'
            )
        );

        return [
            '@context' => 'https://schema.org/',
            '@type' => 'WebSite',

            'name' =>
            config('seo.site_name'),

            'url' => $baseUrl,

            'potentialAction' => [
                '@type' => 'SearchAction',

                'target' =>
                $baseUrl
                    . '/search?q={search_term_string}',

                'query-input' =>
                'required name=search_term_string',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BREADCRUMB JSON-LD
    |--------------------------------------------------------------------------
    */

    protected function breadcrumbStructuredData(
        Product $product
    ): array {
        $baseUrl = config(
            'app.url',
            env(
                'APP_URL',
                'http://localhost'
            )
        );

        return [
            '@context' => 'https://schema.org',

            '@type' => 'BreadcrumbList',

            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => $baseUrl,
                ],

                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Products',
                    'item' =>
                    $baseUrl
                        . '/customer/products',
                ],

                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $product->name,
                    'item' => $product->product_url,
                ],
            ],
        ];
    }
}
