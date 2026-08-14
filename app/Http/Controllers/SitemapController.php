<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $content = Cache::remember('sitemap.xml', 3600, function (): string {
            $products = Product::query()
                ->whereNull('meta_robots')
                ->orWhere('meta_robots', 'like', 'index%')
                ->latest('updated_at')
                ->limit(config('seo.sitemap.limit', 1000))
                ->get();

            $items = '';
            foreach ($products as $product) {
                $loc = $product->slug
                    ? url('/product/' . $product->slug)
                    : url('/customer/products/' . $product->id);

                $image = $product->image_url;
                $imageBlock = '';
                if ($image) {
                    $imageBlock = '<image:image>'
                        . '<image:loc>' . e($image) . '</image:loc>'
                        . ($product->alt_text ? '<image:caption>' . e($product->alt_text) . '</image:caption>' : '')
                        . '</image:image>';
                }

                $items .= '<url>'
                    . '<loc>' . e($loc) . '</loc>'
                    . ($product->updated_at ? '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>' : '')
                    . '<changefreq>weekly</changefreq>'
                    . '<priority>0.8</priority>'
                    . $imageBlock
                    . '</url>';
            }

            return '<?xml version="1.0" encoding="UTF-8" ?>'
                . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'
                . $items
                . '</urlset>';
        });

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
