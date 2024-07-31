<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Jobs\AppUninstalledWebhookJob;
use App\Models\User;

class WehbookResponseController extends Controller
{
    // verify wehbook
    protected function verifyWebhook(Request $request)
    {
        $data = $request->getContent();
        $hmacHeader = $request->header('x-shopify-hmac-sha256');
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_API_SECRET'), true));

        return hash_equals($hmacHeader, $calculatedHmac);
    }

    // webhook topics handler
    public function webhookTopics(Request $request)
    {
        // $requested_url = $request->url();
        // $header = $request->header();
        // Log::info('Webhook Topics Data1:', ['url' => $requested_url]);
        // Log::info('Webhook Topics Data2:', ['header' => $header]);
        // Log::info('Webhook Topics Data3:', ['data' => $request->all()]);
        // Log::info('Webhook Topics Data4:', ['data' => $request->getContent()]);


        $isValid = $this->verifyWebhook($request);
        if(!$isValid) {
            return response()->json(['success' => false], 401);
        }

        $topic = $request->header('x-shopify-topic');
        $data = $request->all();
        $domain = $request->header('x-shopify-shop-domain');
        $shop = User::where('name', $domain)->first();

        switch ($topic) {
            case 'app/uninstalled':
                $this->appUninstalled($data, $shop);
                break;
            case 'orders/paid':
                $this->ordersPaid($data, $shop);
                break;
            case 'carts/update':
                $this->cartsUpdate($data, $shop);
                break;
            case 'customers/data_request':
                break;
            case 'customers/redact':
                break;
            case 'shop/redact':
                break;
            default:
                Log::info('Webhook Topic Not Found:', ['topic' => $topic]);
                break;
        }

        return response()->json(['success' => true], 200);
    }

    // app uninstalled webhook
    public function appUninstalled($data, $shop)
    {
        Log::info('App Uninstalld:', ['data' => $data, 'domain' => $shop]);
        AppUninstalledWebhookJob::dispatch($shop);
        
        return response()->json(['success' => true], 200);
    }

    // orders paid webhook
    public function ordersPaid($data, $shop)
    {
        Log::info('Order Paid:', ['order' => $data, 'shop' => $shop]);
        return response()->json(['success' => true], 200);
    }

    // carts update webhook
    public function cartsUpdate($data, $shop)
    {
        Log::info('Cart Update:', ['cart' => $data['id'], 'shop' => $shop]);
        return response()->json(['success' => true], 200);
    }

    // Mandatory webhooks
    // customers data request webhook
    public function customersDataRequest($data, $shop)
    {
        Log::info('Customer Data Request:', ['data' => $data, 'shop' => $shop]);
        return response()->json(['success' => true], 200);
    }

    // customers redact webhook
    public function customersRedact($data, $shop)
    {
        Log::info('Customer Redact:', ['data' => $data, 'shop' => $shop]);
        return response()->json(['success' => true], 200);
    }

    // shop redact webhook
    public function shopRedact($data, $shop)
    {
        Log::info('Shop Redact:', ['data' => $data, 'shop' => $shop]);
        return response()->json(['success' => true], 200);
    }
}
