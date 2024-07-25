<?php

namespace App\Console\Commands\Category;

use Illuminate\Console\Command;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use App\Models\Category\Category;
use Exception;
use Log;
use DB;

class GetWooCommerceCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will fetch all the categories from the wocommerce';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $woocommerce;

    public function __construct()
    {
        parent::__construct();
        $this->woocommerce = new Client(
            config('wocommerce_api.store_url'),
            config('wocommerce_api.consumer_key'), // Your consumer key
            config('wocommerce_api.consumer_secret'), // Your consumer secret
            [
                'wp_api' => true, // Enable the WP REST API integration
                'version' => 'wc/v3' // WooCommerce WP REST API version
            ]
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $parent_categories = $this->woocommerce->get('products/categories', [
                'order'     => 'asc',
                'per_page'  => 100,
                'parent'    => 0
            ]);
        } catch(HttpClientException $e) {
            Log::info('Last request', [$e->getRequest()]);
            Log::error('Last response', [$e->getResponse()]);
            Log::error('Error while fetching parent categories from wocommerce', [$e->getMessage()]);
            return $this->error($e->getMessage());
        }

        if (empty($parent_categories)) {
            return $this->error("Parent categories data not available from wocommerce");
        }

        try {
            DB::beginTransaction();
            foreach ($parent_categories as $parent_category) {
                // NOTE::Adding this condition, 
                // while testing REST API found some category which is not on the frontend,
                // those categories display default value was product
                if ($parent_category->display == "default") {
                    //find if category is already exists or not
                    $category = Category::where('wocommerce_category_id', $parent_category->id)->first();
                    if (!$category) {
                        $this->info("New parent category found : " . $parent_category->name);
                        //create new category
                        Category::create([
                            'category_name' => $parent_category->name,
                            'wocommerce_slug' => $parent_category->slug,
                            'wocommerce_category_id' => $parent_category->id
                        ]);
                    } else {
                        // category might have update from wocommerce in name and slug
                        if (($category->category_name != $parent_category->name) || ($category->wocommerce_slug != $parent_category->slug)) {
                            //make sure you are doing update to same category
                            if ($category->wocommerce_category_id == $parent_category->id) {
                                $this->info("Parent category updated from wocommerce : " . $parent_category->name);
                                $category->update([
                                    'category_name' => $parent_category->name,
                                    'wocommerce_slug' => $parent_category->slug
                                ]);
                            }
                        } else {
                            $this->info("No updates in {$category->id} - {$category->category_name}");
                        }
                    }
                }
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            Log::error('Error while saving parent categories from wocommerce', [$e->getMessage()]);
            return $this->error($e->getMessage());
        }

        //get all the parent category from the db and will create children for each category one by one
        try {
            DB::beginTransaction();
            $db_parent_categories = Category::whereNull('parent_category_id')->get();
            if (!$db_parent_categories->count()) {
                Log::error('Parent categories not found in database', [$db_parent_categories]);
                return $this->error("No parent categories available in system database");
            }

            foreach ($db_parent_categories as $db_parent_category) {
                //fetch child categories
                try {
                    $child_categories = $this->woocommerce->get('products/categories', [
                        'order'     => 'asc',
                        'per_page'  => 100,
                        'parent'    => $db_parent_category->wocommerce_category_id
                    ]);
                } catch (HttpClientException $e) {
                    Log::info('Parent category', [$db_parent_category]);
                    Log::info('Last request', [$e->getRequest()]);
                    Log::error('Last response', [$e->getResponse()]);
                    Log::error('Error while fetching child categories from wocommerce', [$e->getMessage()]);
                    $this->error($e->getMessage());
                    continue;
                }

                if (empty($child_categories)) {
                    $this->error("Children categories data not available for the parent category {$db_parent_category->category_name} from wocommerce");
                    continue;
                }

                foreach ($child_categories as $child_category) {
                    if ($child_category->display == "default") {
                        //find if child category is already exists or not
                        $category = Category::where('parent_category_id', $db_parent_category->id)
                                        ->where('wocommerce_category_id', $child_category->id)->first();
                        if (!$category) {
                            $this->info("New child category found : " . $child_category->name);
                            //create new category
                            $db_parent_category->children()->create([
                                'category_name' => $child_category->name,
                                'wocommerce_slug' => $child_category->slug,
                                'wocommerce_category_id' => $child_category->id
                            ]);
                        } else {
                            // category might have update from wocommerce in name and slug
                            if (($category->category_name != $child_category->name) || ($category->wocommerce_slug != $child_category->slug)) {
                                //make sure you are doing update to same category
                                if ($category->wocommerce_category_id == $child_category->id) {
                                    $this->info("Child category updated from wocommerce : " . $child_category->name);
                                    $category->update([
                                        'category_name' => $child_category->name,
                                        'wocommerce_slug' => $child_category->slug
                                    ]);
                                }
                            } else {
                                $this->info("No updates in {$category->id} - {$category->category_name}");
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            Log::error('Error while saving child categories from wocommerce', [$e->getMessage()]);
            return $this->error($e->getMessage());
        }
    }
}
