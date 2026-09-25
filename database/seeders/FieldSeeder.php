<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::insert([
            ['name' => 'Futsal A',    'type' => 'futsal',    'price_per_hour' => 150000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Futsal B',    'type' => 'futsal',    'price_per_hour' => 130000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Badminton 1', 'type' => 'badminton', 'price_per_hour' => 60000,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
