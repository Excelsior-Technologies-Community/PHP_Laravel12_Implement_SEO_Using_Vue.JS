<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'alt_text',
        'size',
        'price',

        'seo_image',
        'seo_image_alt',

        'og_image',
        'og_image_alt',

        'seo_meta_title',
        'og_meta_title',

        'seo_meta_keywords',
        'og_meta_keywords',

        'seo_meta_description',
        'og_meta_description',

        'seo_canonical',
        'meta_robots',
    ];

    protected $appends = [
        'image_url',
        'og_image_url',
        'seo_image_url',
        'meta_robots_index',
        'product_url',

        // SEO Audit
        'seo_score',
        'seo_issues',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $product) {
            $product->slug = self::makeSlug(
                $product->slug,
                $product->name,
                $product->id
            );
        });

        static::updating(function (self $product) {
            $product->slug = self::makeSlug(
                $product->slug,
                $product->name,
                $product->id
            );
        });
    }

    public static function makeSlug(
        ?string $slug,
        string $name,
        ?int $ignoringId = null
    ): string {
        if (! empty($slug)) {
            $base = Str::slug($slug);
        } else {
            $base = Str::slug($name);
        }

        if ($base === '') {
            $base = 'product';
        }

        $original = $base;
        $i = 2;

        while (
            self::where('id', '!=', $ignoringId)
                ->where('slug', $base)
                ->exists()
        ) {
            $base = $original . '-' . $i++;
        }

        return $base;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? asset('product_images/' . $this->image)
            : null;
    }

    public function getOgImageUrlAttribute(): ?string
    {
        return $this->og_image
            ? asset('product_images/' . $this->og_image)
            : null;
    }

    public function getSeoImageUrlAttribute(): ?string
    {
        return $this->seo_image
            ? asset('product_images/' . $this->seo_image)
            : null;
    }

    public function getMetaRobotsIndexAttribute(): bool
    {
        return ! in_array(
            strtolower((string) $this->meta_robots),
            [
                'noindex',
                'none',
                'noimageindex',
                'nosnippet',
            ],
            true
        );
    }

    public function getProductUrlAttribute(): string
    {
        return $this->slug
            ? url('/product/' . $this->slug)
            : url('/customer/products/' . $this->id);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | SEO HEALTH SCORE
    |--------------------------------------------------------------------------
    */

    public function getSeoScoreAttribute(): int
    {
        $score = 0;

        // 1. SEO Meta Title - 15
        if (
            ! empty($this->seo_meta_title) &&
            mb_strlen($this->seo_meta_title) <= 60
        ) {
            $score += 15;
        }

        // 2. SEO Meta Description - 15
        if (
            ! empty($this->seo_meta_description) &&
            mb_strlen($this->seo_meta_description) <= 160
        ) {
            $score += 15;
        }

        // 3. SEO Keywords - 10
        if (! empty(trim((string) $this->seo_meta_keywords))) {
            $score += 10;
        }

        // 4. Canonical URL - 10
        if (! empty($this->seo_canonical)) {
            $score += 10;
        }

        // 5. SEO Friendly Slug - 10
        if (
            ! empty($this->slug) &&
            $this->slug === Str::slug($this->slug)
        ) {
            $score += 10;
        }

        // 6. Main Image Alt Text - 10
        if (! empty(trim((string) $this->alt_text))) {
            $score += 10;
        }

        // 7. OG Title - 10
        if (! empty($this->og_meta_title)) {
            $score += 10;
        }

        // 8. OG Description - 10
        if (! empty($this->og_meta_description)) {
            $score += 10;
        }

        // 9. OG Image - 10
        if (! empty($this->og_image)) {
            $score += 10;
        }

        return $score;
    }

    /*
    |--------------------------------------------------------------------------
    | SEO AUDIT ISSUES
    |--------------------------------------------------------------------------
    */

    public function getSeoIssuesAttribute(): array
    {
        $issues = [];

        if (empty($this->seo_meta_title)) {
            $issues[] = 'Add an SEO meta title.';
        } elseif (mb_strlen($this->seo_meta_title) > 60) {
            $issues[] = 'SEO meta title should be 60 characters or less.';
        }

        if (empty($this->seo_meta_description)) {
            $issues[] = 'Add an SEO meta description.';
        } elseif (mb_strlen($this->seo_meta_description) > 160) {
            $issues[] = 'SEO meta description should be 160 characters or less.';
        }

        if (empty(trim((string) $this->seo_meta_keywords))) {
            $issues[] = 'Add SEO keywords.';
        }

        if (empty($this->seo_canonical)) {
            $issues[] = 'Add a canonical URL.';
        }

        if (empty($this->slug)) {
            $issues[] = 'Add an SEO-friendly slug.';
        }

        if (empty(trim((string) $this->alt_text))) {
            $issues[] = 'Add alt text to the main product image.';
        }

        if (empty($this->og_meta_title)) {
            $issues[] = 'Add an Open Graph title.';
        }

        if (empty($this->og_meta_description)) {
            $issues[] = 'Add an Open Graph description.';
        }

        if (empty($this->og_image)) {
            $issues[] = 'Add an Open Graph image.';
        }

        if (empty($issues)) {
            $issues[] = 'Great! No major SEO issues were found.';
        }

        return $issues;
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }
}