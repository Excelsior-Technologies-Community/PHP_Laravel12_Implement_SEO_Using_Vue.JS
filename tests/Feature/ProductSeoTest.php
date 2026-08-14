<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeoTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'name' => 'Premium Running Shoes',
            'size' => 'M',
            'price' => 2499.00,
            'alt_text' => 'Red running shoes on a track',
            'og_image_alt' => 'Running shoes hero shot',
            'seo_meta_title' => 'Premium Running Shoes - Best Price 2025',
            'og_meta_title' => 'Premium Running Shoes',
            'seo_meta_description' => 'Shop premium running shoes online. Free shipping and easy returns.',
            'og_meta_description' => 'Top quality running shoes. Shop now.',
            'seo_meta_keywords' => 'running shoes, sports, footwear',
            'og_meta_keywords' => 'running shoes, sports, footwear',
            'seo_canonical' => 'https://example.com/product/premium-running-shoes',
            'meta_robots' => 'index,follow',
        ]);
    }

    public function test_customer_detail_page_renders_server_side_seo_meta(): void
    {
        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertStatus(200);
        $response->assertSee('<title>Premium Running Shoes - Best Price 2025', false);
        $response->assertSee('name="description" content="Shop premium running shoes online. Free shipping and easy returns."', false);
        $response->assertSee('name="keywords" content="running shoes, sports, footwear"', false);
        $response->assertSee('rel="canonical" href="https://example.com/product/premium-running-shoes"', false);
        $response->assertSee('name="robots" content="index,follow"', false);
    }

    public function test_customer_detail_page_renders_open_graph_tags(): void
    {
        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertStatus(200);
        $response->assertSee('property="og:title" content="Premium Running Shoes"', false);
        $response->assertSee('property="og:description" content="Top quality running shoes. Shop now."', false);
        $response->assertSee('property="og:image:alt" content="Running shoes hero shot"', false);
        $response->assertSee('property="og:type" content="product"', false);
    }

    public function test_customer_detail_page_renders_twitter_card_tags(): void
    {
        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertSee('name="twitter:card" content="summary_large_image"', false);
        $response->assertSee('name="twitter:title" content="Premium Running Shoes - Best Price 2025"', false);
    }

    public function test_customer_detail_page_renders_json_ld_structured_data(): void
    {
        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"Product"', false);
        $response->assertSee('"priceCurrency":"INR"', false);
        $response->assertSee('"price":2499', false);
        $response->assertSee('InStock', false);
    }

    public function test_customer_detail_page_has_viewport_meta(): void
    {
        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertSee('name="viewport" content="width=device-width, initial-scale=1.0"', false);
    }

    public function test_noindex_product_is_not_indexed_by_robots(): void
    {
        $this->product->update(['meta_robots' => 'noindex,nofollow']);

        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertSee('name="robots" content="noindex,nofollow"', false);
    }

    public function test_slug_based_friendly_url_renders_the_product(): void
    {
        // Generate a slug explicitly
        $this->product->update(['slug' => 'premium-running-shoes']);

        $response = $this->get('/product/premium-running-shoes');

        $response->assertStatus(200);
        $response->assertSee('Premium Running Shoes', false);
    }

    public function test_slug_route_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/product/does-not-exist');
        $response->assertNotFound();
    }

    public function test_sitemap_returns_valid_xml_with_product(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('http://www.sitemaps.org/schemas/sitemap/0.9', false);
        $response->assertSee('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', false);
    }

    public function test_robots_txt_references_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Sitemap:');
    }

    public function test_api_can_list_products(): void
    {
        Product::factory()->count(2)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'price']]])
            ->assertJsonFragment(['name' => 'Premium Running Shoes']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/products/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price']);
    }

    public function test_slug_is_auto_generated_from_name(): void
    {
        $product = Product::create([
            'name' => 'Wireless Headphones XL',
            'size' => 'One Size',
            'price' => 1999.00,
        ]);

        $this->assertNotNull($product->slug);
        $this->assertEquals('wireless-headphones-xl', $product->slug);
    }

    public function test_product_url_accesssor_resolves_friendly_url(): void
    {
        $this->product->update(['slug' => 'premium-running-shoes']);
        $this->product->refresh();

        $this->assertStringContainsString('/product/premium-running-shoes', $this->product->product_url);
    }

    public function test_seo_fallback_uses_config_defaults(): void
    {
        Product::where('id', $this->product->id)->update([
            'seo_meta_title' => null,
            'seo_meta_description' => null,
            'seo_canonical' => null,
            'name' => 'Super Widget',
        ]);
        $this->product->refresh();

        $response = $this->get('/customer/products/' . $this->product->id);

        $response->assertSee('<title>Super Widget - Buy Super Widget Online at', false);
    }
}
