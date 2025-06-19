<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in dependency order
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            BorrowStatusSeeder::class,
            RequestStatusSeeder::class,
            PurchaseInvoiceSeeder::class,
            ItemSeeder::class,
        ]);

        // Create users with roles
        $users = User::factory(20)->create();
        foreach ($users as $user) {
            $user->role_id = rand(1, 5); // Assign random role
            $user->save();
        }

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@trackventory.com',
            'role_id' => 1, // Admin role
        ]);

        // Seed dependent tables
        $this->call([
            PurchaseRequestSeeder::class,
            ItemBorrowedSeeder::class,
        ]);
    }
}
