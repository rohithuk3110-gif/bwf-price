<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_lists', function (Blueprint $t) {
            $t->id(); $t->string('code')->unique(); $t->string('label');
            $t->string('method')->default('MULT'); // MULT | MARGIN
            $t->decimal('factor', 8, 4)->default(1); $t->boolean('is_default')->default(false); $t->timestamps();
        });
        Schema::create('pricing_rules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete(); // null = family/global
            $t->string('scope')->default('GLOBAL');
            $t->string('code'); $t->string('label'); $t->string('component'); // BASE|OPTION|COLOUR|GLASS|HARDWARE|DELIVERY...
            $t->string('method');   // FIXED|PER_M2|PCT_BASE|PER_MM_OVER|PER_BAR
            $t->decimal('value', 12, 4)->default(0);
            $t->decimal('min_charge', 10, 2)->nullable(); $t->decimal('max_charge', 10, 2)->nullable();
            $t->decimal('waste_factor', 6, 4)->default(0);
            $t->string('per_unit')->nullable(); // OPENER | LIGHT | null
            $t->string('condition_attr')->nullable(); $t->string('condition_value')->nullable();
            $t->boolean('condition_negate')->default(false);
            $t->boolean('is_placeholder')->default(false); $t->boolean('is_verified')->default(false);
            $t->date('valid_from')->nullable(); $t->date('valid_to')->nullable();
            $t->integer('priority')->default(100); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('validation_rules', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('scope')->default('GLOBAL');
            $t->string('rule_type'); // MIN|MAX|MAX_AREA|FORCE
            $t->string('attribute_code')->nullable(); $t->decimal('value_number', 12, 2)->nullable();
            $t->string('force_attribute')->nullable(); $t->string('force_value')->nullable();
            $t->string('severity')->default('ERROR'); $t->string('message');
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('vat_rules', function (Blueprint $t) {
            $t->id(); $t->string('code')->unique(); $t->string('label');
            $t->decimal('rate', 6, 4); $t->boolean('is_default')->default(false); $t->timestamps();
        });
        Schema::create('delivery_rules', function (Blueprint $t) {
            $t->id(); $t->string('label'); $t->string('method')->default('PER_ORDER');
            $t->decimal('amount', 10, 2); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('discount_rules', function (Blueprint $t) {
            $t->id(); $t->string('code')->unique(); $t->string('label');
            $t->decimal('percent', 6, 4)->default(0); $t->string('applies_to')->default('ALL');
            $t->decimal('min_margin_floor', 6, 4)->default(0.18);
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
    }
    public function down(): void
    { Schema::dropIfExists('discount_rules'); Schema::dropIfExists('delivery_rules'); Schema::dropIfExists('vat_rules'); Schema::dropIfExists('validation_rules'); Schema::dropIfExists('pricing_rules'); Schema::dropIfExists('price_lists'); }
};
