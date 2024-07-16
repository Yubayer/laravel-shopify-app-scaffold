<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class WebhookInstallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;

    /**
     * Create a new job instance.
     */
    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $shop = $this->shop;

            $app_uninstall_address = env('WEBHOOK_URL') . '/webhook/app/uninstalled';
            $orders_paid_address = env('WEBHOOK_URL') . '/webhook/orders/paid';
            $carts_update_address = env('WEBHOOK_URL') . '/webhook/carts/update';

            $app_install_webhook_query = [
                'topic' => 'app/uninstalled',
                'address' => $app_uninstall_address,
                'format' => 'json'
            ];

            $orders_paid_webhook_query = [
                'topic' => 'orders/paid',
                'address' => $orders_paid_address,
                'format' => 'json'
            ];

            $carts_update_webhook_query = [
                'topic' => 'carts/update',
                'address' => $carts_update_address,
                'format' => 'json'
            ];

            $webhook_queries = [$app_install_webhook_query, $orders_paid_webhook_query, $carts_update_webhook_query];

            foreach($webhook_queries as $webhook_query) {
                $response = $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', ['webhook' => $webhook_query]);

                if ($response['errors']) {
                    Log::error('Failed to register webhook:', ['response' => $response]);
                } else {
                    Log::info('Webhook registered successfully ', ['response' => $response]);
                }
            }

        } catch(\Exception $e) {
            Log::error('WebhookInstallJob ---- failed, catch: ', ['error' => $e->getMessage()]);
        }
    }
}
