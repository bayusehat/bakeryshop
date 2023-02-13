<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\KategoriFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemMaster>
 */
class ItemMasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama_item' => fake()->word("id_ID"),
            'id_kategori' => fake()->randomNumber(1),
            'expired_day' => fake()->randomNumber(1)
            // 'nama_item' => fake()->word(),
            // 'id_kategori' => fake()->randomNumber(1),
            // 'expired_day' => fake()->randomNumber(1)
        ];
    }
}
