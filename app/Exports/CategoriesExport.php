<?php

namespace App\Exports;

use App\Models\Category\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class CategoriesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $category = DB::table('categories as a')->join('categories as b', 'a.id', '=', 'b.parent_category_id')->select('a.category_name as categoryname','b.category_name as subcategory','b.commission')->get();
        return $category;
    }
    public function headings(): array
    {
        return [
            'category_name',
            'sub_category',
            'commission'
        ];
    }
}
