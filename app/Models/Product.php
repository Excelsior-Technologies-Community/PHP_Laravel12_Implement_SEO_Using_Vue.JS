<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image', 'alt_text', 'size', 'price',

        'seo_image', 'seo_image_alt',
        'og_image', 'og_image_alt',

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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $product) {
            $product->slug = self::makeSlug($product->slug, $product->name, $product->id);
        });

        static::updating(function (self $product) {
            $product->slug = self::makeSlug($product->slug, $product->name, $product->id);
        });
    }

    public static function makeSlug(?string $slug, string $name, ?int $ignoringId = null): string
    {
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
        while (self::where('id', '!=', $ignoringId)->where('slug', $base)->exists()) {
            $base = $original . '-' . $i++;
        }

        return $base;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('product_images/' . $this->image) : null;
    }

    public function getOgImageUrlAttribute(): ?string
    {
        return $this->og_image ? asset('product_images/' . $this->og_image) : null;
    }

    public function getSeoImageUrlAttribute(): ?string
    {
        return $this->seo_image ? asset('product_images/' . $this->seo_image) : null;
    }

    public function getMetaRobotsIndexAttribute(): bool
    {
        return ! in_array(
            strtolower((string) ($this->meta_robots)),
            ['noindex', 'none', 'noimageindex', 'nosnippet'],
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

    protected static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }
}
