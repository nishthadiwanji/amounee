<?php

namespace App\Exports;

use App\Models\Product\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $product=DB::table('products')->join('categories', 'categories.id', '=', 'products.category_id')->join('artisans','artisans.id', '=', 'products.artisan_id')->select('products.product_name','categories.category_name','artisans.first_name','artisans.last_name','products.selling_price','products.commision_amount','products.stock','products.stock_status', DB::raw('(case when products.delisted = 1 then "delisted" when products.status="rejected" then "rejected" when products.status="pending" then "pending" else "approved" end) as status'))->get();
        return $product;
    }
    public function headings(): array
    {
        return [
            'product_name',
            'category_name',
            'artisan_first_name',
            'artisan_last_name',
            'sales_price',
            'commission',
            'stock',
            'stock_status',
            'status'
        ];
    }
}
