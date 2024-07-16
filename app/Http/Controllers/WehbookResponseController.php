<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Osiset\ShopifyApp\Util\Webhook;

use App\Jobs\AppUninstalledWebhookJob;

class WehbookResponseController extends Controller
{
    // app uninstalled webhook
    public function appUninstalled(Request $request)
    {
        $isValid = $this->verifyWebhook($request);
        if(!$isValid) {
            return response()->json(['success' => false], 401);
        }

        $data = $request->all();
        $domain = $request->header('x-shopify-shop-domain');
        if(!empty($domain)) {
            AppUninstalledWebhookJob::dispatch($domain);
        }
        
        return response()->json(['success' => true], 200);
    }

    // orders paid webhook
    public function ordersPaid(Request $request)
    {
        $isValid = $this->verifyWebhook($request);
        if(!$isValid) {
            return response()->json(['success' => false], 401);
        }

        $data = $request->all();
        Log::info('Order Paid:', ['order' => $data]);
        return response()->json(['success' => true], 200);
    }

    // carts update webhook
    public function cartsUpdate(Request $request)
    {
        $isValid = $this->verifyWebhook($request);
        if(!$isValid) {
            return response()->json(['success' => false], 401);
        }
        $data = $request->all();
        $header = $request->header();
        Log::info('Cart Update:', ['cart' => $data, 'header' => $header]);
        return response()->json(['success' => true], 200);
    }

    // verify wehbook
    protected function verifyWebhook(Request $request)
    {
        $data = $request->getContent();
        $hmacHeader = $request->header('x-shopify-hmac-sha256');
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_API_SECRET'), true));

        return hash_equals($hmacHeader, $calculatedHmac);
    }
}
