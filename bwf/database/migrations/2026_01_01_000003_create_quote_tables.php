<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email')->nullable(); $t->string('phone')->nullable();
            $t->string('postcode')->nullable(); $t->foreignId('price_list_id')->nullable()->constrained(); $t->timestamps();
        });
        Schema::create('quotes', function (Blueprint $t) {
            $t->id(); $t->string('reference')->unique();
            $t->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $t->string('price_list_code')->default('RETAIL'); $t->string('vat_code')->default('STANDARD');
            $t->string('status')->default('draft');
            $t->decimal('items_total', 12, 2)->default(0); $t->decimal('delivery', 10, 2)->default(0);
            $t->decimal('vat_amount', 12, 2)->default(0); $t->decimal('grand_total', 12, 2)->default(0);
            $t->string('lead_time')->nullable(); $t->date('valid_until')->nullable(); $t->timestamps();
        });
        Schema::create('quote_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained();
            $t->unsignedInteger('quantity')->default(1);
            $t->unsignedInteger('width_mm')->nullable(); $t->unsignedInteger('height_mm')->nullable();
            $t->json('configuration'); $t->json('breakdown'); // frozen snapshot — quotes never reprice
            $t->decimal('unit_price', 12, 2); $t->decimal('line_total', 12, 2); $t->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action'); $t->string('entity'); $t->unsignedBigInteger('entity_id')->nullable();
            $t->json('old_value')->nullable(); $t->json('new_value')->nullable(); $t->timestamps();
        });
    }
    public function down(): void
    { Schema::dropIfExists('audit_logs'); Schema::dropIfExists('quote_items'); Schema::dropIfExists('quotes'); Schema::dropIfExists('customers'); }
};
