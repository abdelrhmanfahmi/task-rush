<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'product one',
            'price' => 100,
            'quantity' => 10
        ]);

        Product::create([
            'name' => 'product two',
            'price' => 200,
            'quantity' => 20
        ]);
    }
}
