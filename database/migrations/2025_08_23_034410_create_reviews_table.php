<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('title', 180)->nullable();
            $table->text('comments')->nullable();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->timestamps();
            $table->unique(['customer_id','product_id']); // one review per product by a customer
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
