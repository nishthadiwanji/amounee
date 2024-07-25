<?php

use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'artisan_id' => '1',
            'category_id' => '1',
            'sub_category_id' => '1',
            'product_name' => Str::random(10),
            'sku' => Str::random(10),
            'stock_status' => 'In Stock',
            'stock' => '20',
            'base_price' => Str::random(10),
            'hsn_code' => Str::random(10),
            'selling_price' => 45000,
            'short_desc' => Str::random(10)
        ]);
    }
}
