<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->dateTime('orderdate');
            $table->string('orderstatus', 60);
            $table->decimal('totalprice', 12, 2);
            $table->timestamps();
            $table->index(['customer_id','orderdate']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
