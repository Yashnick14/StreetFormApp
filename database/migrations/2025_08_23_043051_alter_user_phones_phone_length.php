<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_phones', function (Blueprint $table) {
            $table->string('phone', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_phones', function (Blueprint $table) {
            $table->string('phone', 10)->change(); // or whatever it was before
        });
    }
};
