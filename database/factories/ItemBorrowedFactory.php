<?php

namespace Database\Factories;

use App\Models\Item_Borrowed;
use App\Models\User;
use App\Models\Item;
use App\Models\Borrow_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item_Borrowed>
 */
class ItemBorrowedFactory extends Factory
{
    protected $model = Item_Borrowed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowDate = $this->faker->dateTimeBetween('-2 months', 'now');
        $dueDate = (clone $borrowDate)->modify('+' . $this->faker->numberBetween(7, 30) . ' days');
        $returnDate = $this->faker->optional(0.6)->dateTimeBetween($borrowDate, 'now');

        return [
            'user_id' => 1, // Will be set by seeder
            'item_id' => 1, // Will be set by seeder
            'borrow_status_id' => 1, // Will be set by seeder
            'quantity' => $this->faker->numberBetween(1, 5),
            'borrow_date' => $borrowDate->format('Y-m-d'),
            'return_date' => $returnDate ? $returnDate->format('Y-m-d') : null,
            'due_date' => $dueDate->format('Y-m-d'),
            'fine' => $this->faker->optional(0.3)->randomFloat(2, 0, 100),
        ];
    }
}
