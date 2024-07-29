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
        try {
            $shop = $this->shop;

            $app_topics_url = env('WEBHOOK_URL') . '/webhook/topics/all';

            $webhook_topics = [
                'app/uninstalled',
                'orders/paid',
                'carts/update'
            ];

            $webhook_queries = [];

            foreach ($webhook_topics as $topic) {
                $webhook_queries[] = [
                    'topic' => $topic,
                    'address' => $app_topics_url,
                    'format' => 'json'
                ];
            }

            

            Log::info('data:', ['webhook_queries' => $webhook_queries]);

            foreach ($webhook_queries as $webhook_query) {
                $response = $shop->api()->rest('POST', '/admin/api/2024-04/webhooks.json', ['webhook' => $webhook_query]);

                if ($response['errors']) {
                    Log::error('Failed to register webhook:', ['response' => $response]);
                } else {
                    Log::info('Webhook registered successfully ', ['response' => $response]);
                }
            }
        } catch (\Exception $e) {
            Log::error('WebhookInstallJob ---- failed, catch: ', ['error' => $e->getMessage()]);
        }
    }
}
