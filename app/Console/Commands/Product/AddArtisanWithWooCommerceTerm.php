<?php

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use App\Models\Artisan\Artisan;
use Log;

class AddArtisanWithWooCommerceTerm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:generate-artisan-term';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate product term for each artisan to map with product attribute in woocommerce';

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
            $attributes = $this->woocommerce->get('products/attributes');
            $attribute_id = 0;
            if (!empty($attributes)) {
                //TODO::need to improve below code to get the information
                foreach ($attributes as $attribute) {
                    if ($attribute->slug == 'pa_artisan_info') {
                        $attribute_id = $attribute->id;
                        break;
                    }
                }
            }
            if ($attribute_id == 0) {
                return $this->error("Artisan Info product attribute not found");
            }
            //TODO::remove take before going on live
            $artisans = Artisan::select('id', 'first_name', 'last_name')->take(5)->get();
            foreach ($artisans as $artisan) {
                try {
                    $data = [
                        'name' => $artisan->fullName() . '_' . $artisan->id_token
                    ];
                    $response = $this->woocommerce->post("products/attributes/$attribute_id/terms", $data);
                    $this->info("New product attribute term created as as - {$response->name} created");
                } catch (HttpClientException $e) {
                    Log::error('Error while creating product attribute term', [$e->getMessage()]);
                    $this->error("Error for Artisan {$artisan->fullName()}");
                    $this->error($e->getMessage());
                }
            }
        } catch (HttpClientException $e) {
            Log::error('Error while creating product attribute term', [$e->getMessage()]);
            return $this->error($e->getMessage());
        }
    }
}
