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
