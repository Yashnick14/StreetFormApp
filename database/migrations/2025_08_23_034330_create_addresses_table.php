<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('street', 255);
            $table->string('city', 120);
            $table->string('province', 120)->nullable();
            $table->string('postalcode', 20)->nullable();
            $table->string('country', 120);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
