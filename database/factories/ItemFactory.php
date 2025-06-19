<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'item_name' => $this->faker->randomElement([
                'Laptop',
                'Projector',
                'Office Chair',
                'Printer',
                'Whiteboard',
                'Calculator',
                'Microscope',
                'Basketball',
                'Scissors',
                'Notebook'
            ]) . ' ' . $this->faker->word(),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
