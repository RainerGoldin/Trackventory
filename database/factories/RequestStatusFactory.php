<?php

namespace Database\Factories;

use App\Models\Request_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request_Status>
 */
class RequestStatusFactory extends Factory
{
    protected $model = Request_Status::class;

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
            ['status_name' => 'Rejected', 'badge_color' => '#DC3545'],
            ['status_name' => 'In Progress', 'badge_color' => '#007BFF'],
            ['status_name' => 'Completed', 'badge_color' => '#6C757D'],
        ];

        $status = $this->faker->randomElement($statuses);

        return [
            'status_name' => $status['status_name'],
            'badge_color' => $status['badge_color'],
        ];
    }
}
