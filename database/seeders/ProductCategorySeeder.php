<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            ['name' => 'เสาไฟ',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ราวกั้นอันตราย','created_at' => $now, 'updated_at' => $now],
            ['name' => 'สีเทอร์โมพลาสติก','created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('product_categories')->insert($categories);
    }
}
