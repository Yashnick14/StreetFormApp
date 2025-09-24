<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['id' => 1, 'name' => 'Men', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Women', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
