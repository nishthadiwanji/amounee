<?php

use Illuminate\Database\Seeder;
use App\Models\Category\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = config('constant.categories');

        foreach ($categories as $parent_category_name => $child_categories) {
            \Log::info('parent_category',[$parent_category_name]);
            $parent_category = Category::create([
                'category_name' => $parent_category_name
            ]);
            if ($parent_category) {
                foreach ($child_categories as $category) {
                    \Log::info('category', [$category]);
                    $parent_category->children()->create([
                        'category_name' => $category
                    ]);
                }
            }
        }
    }
}
