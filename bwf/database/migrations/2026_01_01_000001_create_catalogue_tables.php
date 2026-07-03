<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id(); $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('name'); $t->string('slug')->unique(); $t->text('blurb')->nullable();
            $t->integer('sort_order')->default(0); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('attribute_groups', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->foreignId('attribute_group_id')->constrained()->cascadeOnDelete();
            $t->string('sku')->unique(); $t->string('slug')->unique();
            $t->string('name'); $t->text('description')->nullable();
            $t->unsignedTinyInteger('layout_cols')->default(1);
            $t->unsignedTinyInteger('layout_rows')->default(1);
            $t->json('opener_cells')->nullable();
            $t->decimal('base_price', 10, 2)->default(0);
            $t->boolean('price_verified')->default(false);
            $t->integer('sort_order')->default(0); $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('product_images', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('path'); $t->string('alt')->nullable(); $t->integer('sort_order')->default(0); $t->timestamps();
        });
        Schema::create('attributes', function (Blueprint $t) {
            $t->id(); $t->foreignId('attribute_group_id')->constrained()->cascadeOnDelete();
            $t->string('code'); $t->string('label'); $t->string('input_type'); // number|select|swatch|bool
            $t->string('default_value')->nullable();
            $t->string('parent_code')->nullable(); $t->string('parent_trigger')->nullable();
            $t->integer('sort_order')->default(0); $t->boolean('is_required')->default(false);
            $t->timestamps(); $t->unique(['attribute_group_id', 'code']);
        });
        Schema::create('attribute_options', function (Blueprint $t) {
            $t->id(); $t->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $t->string('code'); $t->string('label'); $t->string('swatch_hex')->nullable();
            $t->unsignedSmallInteger('lead_time_days')->nullable();
            $t->integer('sort_order')->default(0); $t->boolean('is_active')->default(true);
            $t->timestamps(); $t->unique(['attribute_id', 'code']);
        });
    }
    public function down(): void
    { Schema::dropIfExists('attribute_options'); Schema::dropIfExists('attributes'); Schema::dropIfExists('product_images'); Schema::dropIfExists('products'); Schema::dropIfExists('attribute_groups'); Schema::dropIfExists('categories'); }
};
