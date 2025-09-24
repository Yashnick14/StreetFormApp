<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete(); // 1 order = 1 payment
            $table->string('paymentmethod', 60);
            $table->decimal('amount', 12, 2);
            $table->dateTime('paymentdate')->nullable();
            $table->string('paymentstatus', 40)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
