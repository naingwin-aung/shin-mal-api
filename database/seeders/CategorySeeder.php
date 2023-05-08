<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ကြက်သား',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '၀က်သား',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'အမဲသား',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ဆိတ်သား',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ငါး',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ပုဇွန်',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
