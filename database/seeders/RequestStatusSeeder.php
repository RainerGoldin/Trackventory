<?php

namespace Database\Seeders;

use App\Models\Request_Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'Pending', 'badge_color' => '#FFA500'],
            ['status_name' => 'Approved', 'badge_color' => '#28A745'],
            ['status_name' => 'Rejected', 'badge_color' => '#DC3545'],
            ['status_name' => 'In Progress', 'badge_color' => '#007BFF'],
            ['status_name' => 'Completed', 'badge_color' => '#6C757D'],
        ];

        foreach ($statuses as $status) {
            Request_Status::create($status);
        }
    }
}
