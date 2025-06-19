<?php

namespace Database\Seeders;

use App\Models\Purchase_Request;
use App\Models\Request_Status;
use App\Models\Category;
use App\Models\Purchase_Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestStatuses = Request_Status::all();
        $categories = Category::all();
        $invoices = Purchase_Invoice::all();

        for ($i = 0; $i < 30; $i++) {
            Purchase_Request::factory()->create([
                'request_status_id' => $requestStatuses->random()->id,
                'category_id' => $categories->random()->id,
                'invoice_id' => rand(0, 1) ? $invoices->random()->id : null,
            ]);
        }
    }
}
