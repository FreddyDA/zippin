<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Producto 1',
                'price' => 100.00,
                'category' => 'Categoría 1',
                'quantity' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Producto 2',
                'price' => 200.00,
                'category' => 'Categoría 2',
                'quantity' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Producto 3',
                'price' => 300.00,
                'category' => 'Categoría 3',
                'quantity' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Producto 4',
                'price' => 400.00,
                'category' => 'Categoría 4',
                'quantity' => 40,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Producto 5',
                'price' => 500.00,
                'category' => 'Categoría 5',
                'quantity' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}