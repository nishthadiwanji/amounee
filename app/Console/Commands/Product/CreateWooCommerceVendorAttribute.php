<?php

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Log;


class CreateWooCommerceVendorAttribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:create-vendor-attribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create new product attributes named as vendor';

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
            $data = [
                'name' => 'Artisan Info',
                'slug' => 'pa_artisan_info',
                'type' => 'select',
                'order_by' => 'menu_order',
                'has_archives' => false
            ];
            $response = $this->woocommerce->post('products/attributes', $data);
            if (isset($response->id) && $response->slug == $data['slug']) {
                return $this->info("New product attributes named as - {$response->name} created");
            }
            return $this->error('Unable to create product attributes');
        } catch (HttpClientException $e) {
            Log::error('Error while creating product attribute', [$e->getMessage()]);
            return $this->error($e->getMessage());
        }
    }
}
