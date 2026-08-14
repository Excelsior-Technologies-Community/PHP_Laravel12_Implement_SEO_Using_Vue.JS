<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $products = [
            [
                'name' => 'Premium Running Shoes',
                'image' => '1768980487_main.jpg',
                'seo_image' => '1768980487_seo.jpg',
                'og_image' => '1768980487_og.jpg',
                'alt_text' => 'Red running shoes on a track at sunrise',
                'seo_image_alt' => 'SEO preview image of running shoes',
                'og_image_alt' => 'Hero shot of running shoes with reflection',
                'size' => 'M',
                'price' => 2499.00,
                'seo_meta_title' => 'Premium Running Shoes - Best Price 2025',
                'og_meta_title' => 'Premium Running Shoes',
                'seo_meta_keywords' => 'running shoes, sports shoes, athletic wear, best price',
                'og_meta_keywords' => 'running shoes, sports, athleisure',
                'seo_meta_description' => 'Shop premium running shoes online. Free shipping and easy returns.',
                'og_meta_description' => 'Top quality running shoes. Shop now.',
                'seo_canonical' => 'https://example.com/product/premium-running-shoes',
                'meta_robots' => 'index,follow',
            ],
            [
                'name' => 'Wireless Headphones',
                'image' => '1768987267_main.jpg',
                'seo_image' => '1768987267_seo.jpg',
                'og_image' => '1768987267_og.jpg',
                'alt_text' => 'Black over-ear wireless headphones on a dark background',
                'seo_image_alt' => 'Wireless headphones SEO preview',
                'og_image_alt' => 'Premium wireless headphones hero banner',
                'size' => 'One Size',
                'price' => 5499.00,
                'seo_meta_title' => 'Wireless Headphones - Noise Cancelling Online',
                'og_meta_title' => 'Noise Cancelling Wireless Headphones',
                'seo_meta_keywords' => 'wireless headphones, noise cancelling, bluetooth, audio',
                'og_meta_keywords' => 'headphones, audio, bluetooth, sound',
                'seo_meta_description' => 'Experience crystal-clear sound with our noise-cancelling wireless headphones.',
                'og_meta_description' => 'Premium audio quality. Shop the sound you feel.',
                'seo_canonical' => 'https://example.com/product/wireless-headphones',
                'meta_robots' => 'index,follow',
            ],
            [
                'name' => 'Leather Wallet',
                'image' => '1768979958.jpg',
                'seo_image' => '1768979981.jpg',
                'og_image' => '1768980075.jpg',
                'alt_text' => 'Brown genuine leather wallet opened to show card slots',
                'seo_image_alt' => 'Leather wallet SEO banner',
                'og_image_alt' => 'Leather wallet product showcase',
                'size' => 'Compact',
                'price' => 1299.00,
                'seo_meta_title' => 'Genuine Leather Wallet - Handcrafted',
                'og_meta_title' => 'Handcrafted Leather Wallet',
                'seo_meta_keywords' => 'leather wallet, bifold, men accessories, handmade',
                'og_meta_keywords' => 'wallet, leather, accessories, gift',
                'seo_meta_description' => 'Handcrafted genuine leather wallet. Premium quality and style for everyday carry.',
                'og_meta_description' => 'Timeless elegance meets function. Shop our leather collection.',
                'seo_canonical' => 'https://example.com/product/leather-wallet',
                'meta_robots' => 'index,follow',
            ],
            [
                'name' => 'Stainless Steel Water Bottle',
                'image' => null,
                'seo_image' => null,
                'og_image' => null,
                'alt_text' => 'Insulated steel water bottle on a wooden table',
                'seo_image_alt' => null,
                'og_image_alt' => null,
                'size' => '500ml',
                'price' => 899.00,
                'seo_meta_title' => 'EcoSteel Water Bottle - 500ml Stainless Steel',
                'og_meta_title' => 'EcoSteel 500ml Water Bottle',
                'seo_meta_keywords' => 'water bottle, stainless steel, insulated, eco friendly',
                'og_meta_keywords' => 'water bottle, eco, steel, drinkware',
                'seo_meta_description' => 'Stay hydrated with our eco-friendly insulated stainless steel water bottle. Keeps drinks hot/cold for 24hrs.',
                'og_meta_description' => 'Sustainable hydration. Leak-proof and stylish.',
                'seo_canonical' => 'https://example.com/product/water-bottle',
                'meta_robots' => 'noindex,nofollow',
            ],
            [
                'name' => 'Yoga Mat Premium',
                'image' => '1768980075.jpg',
                'seo_image' => '1768979958.jpg',
                'og_image' => '1768979981.jpg',
                'alt_text' => 'Blue non-slip yoga mat rolled on a studio floor',
                'seo_image_alt' => 'Yoga mat SEO closeup',
                'og_image_alt' => 'Premium yoga mat in use',
                'size' => '6mm',
                'price' => 1799.00,
                'seo_meta_title' => 'Premium Yoga Mat - Non-Slip 6mm Thickness',
                'og_meta_title' => '',
                'seo_meta_keywords' => '',
                'og_meta_keywords' => '',
                'seo_meta_description' => '',
                'og_meta_description' => '',
                'seo_canonical' => null,
                'meta_robots' => null,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
