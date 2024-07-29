<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Log;

//use user model
use App\Models\User;

//use webhook install job
use App\Jobs\WebhookInstallJob;

class AppInstalledListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        
        try{
            $domain = $event->domain;
            $authShop = User::where('name', $domain)->first();
            $shop_data = $authShop->api()->rest('GET', '/admin/api/2024-04/shop.json')['body']['shop'];
            
            $authShop->domain = $shop_data['myshopify_domain'];
            $authShop->shop_id = $shop_data['id'];
            $authShop->shop_admin_id = "gid://shopify/Shop/".$shop_data['id'];

            //update the user
            $authShop->save();

            //webhook install job dispatch
            WebhookInstallJob::dispatch($authShop);

            Log::info('Shopify App Installed: ', ['domain' => $domain ,'shop' => $authShop, 'shop_data' => $shop_data]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
