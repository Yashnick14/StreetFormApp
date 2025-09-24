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
            $table->string('main_image')->nullable()->after('type');
            $table->string('extra_image_1')->nullable()->after('main_image');
            $table->string('extra_image_2')->nullable()->after('extra_image_1');
            $table->string('extra_image_3')->nullable()->after('extra_image_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['main_image', 'extra_image_1', 'extra_image_2', 'extra_image_3']);
        });
    }
};
