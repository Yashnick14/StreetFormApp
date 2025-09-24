<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); // each admin row maps to a user; unique ensures 1:1
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('admins'); }
};
