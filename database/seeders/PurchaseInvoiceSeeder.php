<?php

namespace Database\Seeders;

use App\Models\Purchase_Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Purchase_Invoice::factory()->count(20)->create();
    }
}
