<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Electronics', 'description' => 'Electronic devices and components'],
            ['category_name' => 'Office Supplies', 'description' => 'General office and administrative supplies'],
            ['category_name' => 'Furniture', 'description' => 'Office and classroom furniture'],
            ['category_name' => 'Books', 'description' => 'Educational and reference books'],
            ['category_name' => 'Laboratory Equipment', 'description' => 'Scientific and research equipment'],
            ['category_name' => 'Audio Visual', 'description' => 'Projectors, speakers, and AV equipment'],
            ['category_name' => 'Computers', 'description' => 'Desktop computers, laptops, and accessories'],
            ['category_name' => 'Sporting Goods', 'description' => 'Sports and physical education equipment'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
