<?php

namespace Database\Seeders;

use App\Models\Borrow_Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'Pending', 'badge_color' => '#FFA500'],
            ['status_name' => 'Approved', 'badge_color' => '#28A745'],
            ['status_name' => 'Borrowed', 'badge_color' => '#007BFF'],
            ['status_name' => 'Returned', 'badge_color' => '#6C757D'],
            ['status_name' => 'Overdue', 'badge_color' => '#DC3545'],
            ['status_name' => 'Lost', 'badge_color' => '#6F42C1'],
        ];

        foreach ($statuses as $status) {
            Borrow_Status::create($status);
        }
    }
}
