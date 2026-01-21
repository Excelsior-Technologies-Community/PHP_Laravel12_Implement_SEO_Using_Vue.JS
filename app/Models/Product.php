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
