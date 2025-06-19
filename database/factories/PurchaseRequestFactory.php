<?php

namespace Database\Factories;

use App\Models\Purchase_Request;
use App\Models\Request_Status;
use App\Models\Category;
use App\Models\Purchase_Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase_Request>
 */
class PurchaseRequestFactory extends Factory
{
    protected $model = Purchase_Request::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 20);
        $pricePerPcs = $this->faker->randomFloat(2, 5, 500);
        $approvedBudget = $this->faker->optional(0.7)->randomFloat(2, $quantity * $pricePerPcs, $quantity * $pricePerPcs * 1.5);
        $usedBudget = $approvedBudget ? $this->faker->optional(0.5)->randomFloat(2, 0, $approvedBudget) : null;

        return [
            'request_status_id' => 1, // Will be set by seeder
            'category_id' => 1, // Will be set by seeder
            'invoice_id' => $this->faker->optional(0.3)->numberBetween(1, 5),
            'requested_by' => $this->faker->name(),
            'approved_by' => $this->faker->optional(0.6)->name(),
            'item_requested' => $this->faker->randomElement([
                'Office Supplies',
                'Computer Equipment',
                'Laboratory Materials',
                'Furniture',
                'Stationery Items',
                'Audio Visual Equipment'
            ]),
            'description' => $this->faker->optional()->sentence(),
            'quantity' => $quantity,
            'price_per_pcs' => $pricePerPcs,
            'request_date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'approved_budget' => $approvedBudget,
            'used_budget' => $usedBudget,
        ];
    }
}
