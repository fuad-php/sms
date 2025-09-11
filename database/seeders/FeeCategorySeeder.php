<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeeCategory;

class FeeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tuition Fee',
                'code' => 'TUITION',
                'description' => 'Monthly tuition fees for academic education',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Transport Fee',
                'code' => 'TRANSPORT',
                'description' => 'School bus transportation charges',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Library Fee',
                'code' => 'LIBRARY',
                'description' => 'Library membership and book borrowing fees',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sports Fee',
                'code' => 'SPORTS',
                'description' => 'Sports activities and equipment fees',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Examination Fee',
                'code' => 'EXAM',
                'description' => 'Examination and assessment fees',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Development Fee',
                'code' => 'DEVELOPMENT',
                'description' => 'School development and infrastructure fees',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Computer Fee',
                'code' => 'COMPUTER',
                'description' => 'Computer lab and technology fees',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Laboratory Fee',
                'code' => 'LAB',
                'description' => 'Science laboratory and equipment fees',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            FeeCategory::create($category);
        }
    }
}
