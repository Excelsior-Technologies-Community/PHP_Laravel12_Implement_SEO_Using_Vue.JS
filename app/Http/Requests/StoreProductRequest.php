<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],

            'seo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'seo_image_alt' => ['nullable', 'string', 'max:255'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],

            'seo_meta_title' => ['nullable', 'string', 'max:60'],
            'og_meta_title' => ['nullable', 'string', 'max:60'],
            'seo_meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo_meta_description' => ['nullable', 'string', 'max:160'],
            'og_meta_description' => ['nullable', 'string', 'max:110'],
            'seo_canonical' => ['nullable', 'url', 'max:2083'],
            'meta_robots' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow', 'noindex', 'none'])],
        ];
    }
}
