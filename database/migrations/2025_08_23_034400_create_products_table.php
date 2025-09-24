<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180);
            $table->text('description')->nullable();
            $table->string('size', 50)->nullable();        // size column per your model
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stockquantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
