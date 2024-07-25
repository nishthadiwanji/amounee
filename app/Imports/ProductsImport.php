<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'product_name' => $row['product_name'],
            'category_name' => $row['category_name'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'sales_price' => $row['sales_price'],
            'commission' => $row['commission'],
            'stock' => $row['stock'],
            'stock_status' => $row['stock_status'],
            'status' => $row['status']
        ]);
    }
}
