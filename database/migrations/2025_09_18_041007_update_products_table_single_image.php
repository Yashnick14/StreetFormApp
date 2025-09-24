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
            // Remove multiple image columns if they exist
            if (Schema::hasColumn('products', 'main_image')) {
                $table->dropColumn('main_image');
            }
            if (Schema::hasColumn('products', 'extra_image_1')) {
                $table->dropColumn('extra_image_1');
            }
            if (Schema::hasColumn('products', 'extra_image_2')) {
                $table->dropColumn('extra_image_2');
            }
            if (Schema::hasColumn('products', 'extra_image_3')) {
                $table->dropColumn('extra_image_3');
            }
            
            // Add single image column if it doesn't exist
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back multiple image columns
            $table->string('main_image')->nullable();
            $table->string('extra_image_1')->nullable();
            $table->string('extra_image_2')->nullable();
            $table->string('extra_image_3')->nullable();
            
            // Remove single image column
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};