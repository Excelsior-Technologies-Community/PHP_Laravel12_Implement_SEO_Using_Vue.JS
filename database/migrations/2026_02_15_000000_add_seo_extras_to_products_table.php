<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->string('alt_text')->nullable()->after('image');
            $table->string('seo_image_alt')->nullable()->after('seo_image');
            $table->string('og_image_alt')->nullable()->after('og_image');
            $table->string('meta_robots')->nullable()->after('seo_canonical');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'alt_text', 'seo_image_alt', 'og_image_alt', 'meta_robots']);
        });
    }
};
