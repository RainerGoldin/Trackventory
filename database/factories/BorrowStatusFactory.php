<?php

namespace Database\Factories;

use App\Models\Borrow_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrow_Status>
 */
class BorrowStatusFactory extends Factory
{
    protected $model = Borrow_Status::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            ['status_name' => 'Pending', 'badge_color' => '#FFA500'],
            ['status_name' => 'Approved', 'badge_color' => '#28A745'],
            ['status_name' => 'Borrowed', 'badge_color' => '#007BFF'],
            ['status_name' => 'Returned', 'badge_color' => '#6C757D'],
            ['status_name' => 'Overdue', 'badge_color' => '#DC3545'],
            ['status_name' => 'Lost', 'badge_color' => '#6F42C1'],
        ];

        $status = $this->faker->randomElement($statuses);

        return [
            'status_name' => $status['status_name'],
            'badge_color' => $status['badge_color'],
        ];
    }
}
