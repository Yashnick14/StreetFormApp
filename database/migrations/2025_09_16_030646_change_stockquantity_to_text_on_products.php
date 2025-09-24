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

        Schema::table('products', function (Blueprint $table) {
            $table->text('stockquantity')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // If you must rollback, cast back to integer (will fail if JSON inside).
            $table->integer('stockquantity')->default(0)->change();
        });
    }
};
