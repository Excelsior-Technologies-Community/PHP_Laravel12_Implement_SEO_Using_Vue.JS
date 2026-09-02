<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )

                    ->orWhere(
                        'slug',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'size',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }


        /*
    |--------------------------------------------------------------------------
    | PRICE FILTER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                (float) $request->input('min_price')
            );
        }


        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                (float) $request->input('max_price')
            );
        }


        /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

        $sortBy = $request->input(
            'sort_by',
            'created_at'
        );

        $sortOrder = $request->input(
            'sort_order',
            'desc'
        );


        $allowedSorts = [
            'name',
            'price',
            'created_at',
            'updated_at',
        ];


        if (!in_array(
            $sortBy,
            $allowedSorts,
            true
        )) {

            $sortBy = 'created_at';
        }


        if (!in_array(
            $sortOrder,
            ['asc', 'desc'],
            true
        )) {

            $sortOrder = 'desc';
        }


        $query->orderBy(
            $sortBy,
            $sortOrder
        );


        /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

        $perPage = (int) $request->input(
            'per_page',
            5
        );


        if (
            $perPage < 1 ||
            $perPage > 100
        ) {

            $perPage = 5;
        }


        $products = $query
            ->paginate($perPage)
            ->withQueryString();


        return response()->json($products);
    }
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $this->handleUpload($request, 'image');
        $data['seo_image'] = $this->handleUpload($request, 'seo_image');
        $data['og_image'] = $this->handleUpload($request, 'og_image');

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleUpload($request, 'image', $product->image);
        }
        if ($request->hasFile('seo_image')) {
            $data['seo_image'] = $this->handleUpload($request, 'seo_image', $product->seo_image);
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->handleUpload($request, 'og_image', $product->og_image);
        }

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }

    protected function handleUpload(Request $request, string $field, $existing = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $existing;
        }

        $file = $request->file($field);
        $name = time() . '_' . \Illuminate\Support\Str::random(6) . '_' . $field . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('product_images'), $name);

        return $name;
    }
}
