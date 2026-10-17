<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->json('images')->nullable();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('on_sale')->default(false);
            $table->string('sku')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['active', 'featured']);
            $table->index(['active', 'is_new']);
            $table->index(['active', 'on_sale']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
