<?php

namespace Database\Seeders;

use App\Models\Item_Borrowed;
use App\Models\User;
use App\Models\Item;
use App\Models\Borrow_Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemBorrowedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $items = Item::all();
        $borrowStatuses = Borrow_Status::all();

        for ($i = 0; $i < 50; $i++) {
            Item_Borrowed::factory()->create([
                'user_id' => $users->random()->id,
                'item_id' => $items->random()->id,
                'borrow_status_id' => $borrowStatuses->random()->id,
            ]);
        }
    }
}
