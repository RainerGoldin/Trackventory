<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific items for each category
        $items = [
            // Electronics
            ['category_id' => 1, 'item_name' => 'Laptop Dell Inspiron 15', 'stock' => 25],
            ['category_id' => 1, 'item_name' => 'iPad Pro 12.9"', 'stock' => 15],
            ['category_id' => 1, 'item_name' => 'Samsung Galaxy Tab S8', 'stock' => 10],
            
            // Office Supplies
            ['category_id' => 2, 'item_name' => 'A4 Copy Paper (500 sheets)', 'stock' => 100],
            ['category_id' => 2, 'item_name' => 'Ballpoint Pens (Blue)', 'stock' => 200],
            ['category_id' => 2, 'item_name' => 'Stapler Heavy Duty', 'stock' => 30],
            
            // Furniture
            ['category_id' => 3, 'item_name' => 'Office Chair Ergonomic', 'stock' => 50],
            ['category_id' => 3, 'item_name' => 'Conference Table 8-Seater', 'stock' => 8],
            ['category_id' => 3, 'item_name' => 'Filing Cabinet 4-Drawer', 'stock' => 20],
            
            // Books
            ['category_id' => 4, 'item_name' => 'Introduction to Computer Science', 'stock' => 75],
            ['category_id' => 4, 'item_name' => 'Advanced Mathematics Textbook', 'stock' => 60],
            ['category_id' => 4, 'item_name' => 'English Literature Anthology', 'stock' => 45],
            
            // Laboratory Equipment
            ['category_id' => 5, 'item_name' => 'Digital Microscope 1000x', 'stock' => 12],
            ['category_id' => 5, 'item_name' => 'Chemistry Lab Kit Complete', 'stock' => 20],
            ['category_id' => 5, 'item_name' => 'Electronic Scale Precision', 'stock' => 8],
            
            // Audio Visual
            ['category_id' => 6, 'item_name' => 'Epson Projector 4K', 'stock' => 15],
            ['category_id' => 6, 'item_name' => 'Wireless Microphone System', 'stock' => 25],
            ['category_id' => 6, 'item_name' => 'Smart TV 65" Samsung', 'stock' => 6],
            
            // Computers
            ['category_id' => 7, 'item_name' => 'Desktop PC Intel i7', 'stock' => 40],
            ['category_id' => 7, 'item_name' => 'MacBook Air M2', 'stock' => 20],
            ['category_id' => 7, 'item_name' => 'Wireless Mouse Logitech', 'stock' => 80],
            
            // Sporting Goods
            ['category_id' => 8, 'item_name' => 'Basketball Official Size', 'stock' => 30],
            ['category_id' => 8, 'item_name' => 'Tennis Racket Professional', 'stock' => 20],
            ['category_id' => 8, 'item_name' => 'Soccer Ball FIFA Approved', 'stock' => 25],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
