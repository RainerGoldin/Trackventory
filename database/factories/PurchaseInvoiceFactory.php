<?php

namespace Database\Factories;

use App\Models\Purchase_Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase_Invoice>
 */
class PurchaseInvoiceFactory extends Factory
{
    protected $model = Purchase_Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 50);
        $pricePerItem = $this->faker->randomFloat(2, 10, 1000);
        $totalPrice = $quantity * $pricePerItem;
        $budget = $totalPrice + $this->faker->randomFloat(2, 0, $totalPrice * 0.2);

        return [
            'item_purchased' => $this->faker->randomElement([
                'Office Chair',
                'Laptop Computer',
                'Projector Screen',
                'Whiteboard Markers',
                'Filing Cabinet',
                'Desk Lamp',
                'Printer Paper',
                'USB Flash Drive'
            ]),
            'total_price' => $totalPrice,
            'budget' => $budget,
            'quantity' => $quantity,
            'img' => $this->faker->optional()->imageUrl(640, 480, 'business'),
        ];
    }
}
