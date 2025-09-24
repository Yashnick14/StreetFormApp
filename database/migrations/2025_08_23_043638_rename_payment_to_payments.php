<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment') && ! Schema::hasTable('payments')) {
            Schema::rename('payment', 'payments');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments') && ! Schema::hasTable('payment')) {
            Schema::rename('payments', 'payment');
        }
    }
};
