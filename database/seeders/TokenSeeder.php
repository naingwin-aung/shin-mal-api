<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $number = [];
        for($i = 1; $i <= 100; $i++) {
            $number[] = [
                'number' => $i,
                'created_at' => now(),
                'updated_at'=> now(),
            ];
        }

        DB::table('tokens')->insert($number);
    }
}
