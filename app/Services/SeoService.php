<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class SeoService
{
    public function forProduct(Product $product, string $url = null): array
    {
        $fallbackUrl = $product->slug
            ? url('/product/' . $product->slug)
            : url('/customer/products/' . $product->id);

        return $this->buildMeta(
            (string) $product->name,
            $product->seo_meta_title,
            $product->seo_meta_description,
            $product->seo_meta_keywords,
            $product->seo_canonical,
            $product->meta_robots,
            $product->og_meta_title,
            $product->og_meta_description,
            $product->og_image_url,
            $product->og_image_alt ?? $product->alt_text,
            $product->seo_canonical ?: $fallbackUrl,
            $url ?: $fallbackUrl,
            $product->image_url,
            $product->alt_text ?? $product->name,
            $product->price
        );
    }

    public function forPage(string $name, string $title = null, string $description = null, string $url = null, string $keywords = null, ?string $robots = null): array
    {
        return $this->buildMeta(
            $name,
            $title,
            $description,
            $keywords,
            null,
            $robots,
            null,
            null,
            null,
            null,
            $url ?: url()->current(),
            $url ?: url()->current(),
            null,
            $name,
            null
        );
    }

    protected function buildMeta(
        string $name,
        ?string $seoTitle,
        ?string $seoDescription,
        ?string $keywords,
        ?string $canonical,
        ?string $robots,
        ?string $ogTitle,
        ?string $ogDescription,
        ?string $ogImage,
        ?string $ogImageAlt,
        ?string $canonicalUrl,
        ?string $url,
        ?string $imageUrl,
        ?string $altText,
        $price = null
    ): array {
        $cfg = config('seo');
        $defaults = $cfg['defaults'];
        $limits = $cfg['limits'];

        $vars = [
            '{name}' => $name,
            '{price}' => (string) $price,
            '{site}' => $cfg['site_name'],
        ];

        $interpolate = function (?string $value, ?string $fallback = null) use ($vars): string {
            $value = trim((string) $value);
            if ($value === '' && $fallback !== null) {
                $value = $this->rawInterpolate($fallback, $vars);
            }
            return $this->rawInterpolate($value, $vars);
        };

        return [
            'title' => $this->crop($interpolate($seoTitle, $defaults['title']), $limits['title']),
            'description' => $this->crop($interpolate($seoDescription, $defaults['description']), $limits['description']),
            'keywords' => $keywords ?? $defaults['keywords'],
            'canonical' => $canonical ?: $canonicalUrl,
            'robots' => $robots ?: $defaults['meta_robots'],

            'og_title' => $this->crop($interpolate($ogTitle ?: $seoTitle, $defaults['og_title']), $limits['og_title']),
            'og_description' => $this->crop($interpolate($ogDescription ?: $seoDescription, $defaults['og_description']), $limits['og_description']),
            'og_type' => $defaults['og_type'],
            'og_locale' => $defaults['og_locale'],
            'og_image' => $ogImage ?: ($cfg['fallback_image'] ?? null),
            'og_image_alt' => $ogImageAlt ?? $altText ?? $name,

            'twitter_card' => $defaults['twitter_card'],
            'twitter_title' => $this->crop($interpolate($seoTitle, $defaults['twitter_title']), $limits['title']),
            'twitter_description' => $this->crop($interpolate($seoDescription, $defaults['twitter_description']), $limits['description']),
            'twitter_image' => $ogImage ?: ($cfg['fallback_image'] ?? null),

            'url' => $url,
            'image_url' => $imageUrl,
            'alt_text' => $altText ?? $name,
        ];
    }

    protected function rawInterpolate(string $value, array $vars): string
    {
        $value = str_replace(
            array_keys($vars),
            array_map('strval', array_values($vars)),
            $value
        );

        return trim($value);
    }

    protected function crop(string $value, int $limit): string
    {
        if ($value === '') {
            return '';
        }
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return mb_substr($value, 0, $limit - 1) . '…';
    }
}
