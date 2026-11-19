<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->foreignId('user_id')->constrained();
            $table->double('price', 8, 2);
            $table->double('discount_price', 8, 2);
            $table->boolean('personalizable')->default(false);
            $table->double('personalization_fee', 8, 2)->nullable();
            $table->json('placements')->nullable(); // Store as JSON for the array ["lid", "body"]
            $table->integer('stock');
            $table->foreignId('currency_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
