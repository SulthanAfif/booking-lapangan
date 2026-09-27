<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            ['name' => 'Futsal A', 'type' => 'futsal', 'price_per_hour' => 150000, 'is_active' => true],
            ['name' => 'Futsal B', 'type' => 'futsal', 'price_per_hour' => 130000, 'is_active' => true],
            ['name' => 'Badminton 1', 'type' => 'badminton', 'price_per_hour' => 60000, 'is_active' => true],
        ];

        foreach ($fields as $field) {
            Field::updateOrCreate(
                ['name' => $field['name']],
                $field,
            );
        }
    }
}
