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
                $this->appUninstalled($data, $domain);
                break;
            case 'orders/paid':
                $this->ordersPaid($data, $domain);
                break;
            case 'carts/update':
                $this->cartsUpdate($data, $domain);
                break;
            default:
                Log::info('Webhook Topic Not Found:', ['topic' => $topic]);
                break;
        }

        return response()->json(['success' => true], 200);
    }

    // app uninstalled webhook
    public function appUninstalled($data, $domain)
    {
        if(!empty($domain)) {
            AppUninstalledWebhookJob::dispatch($domain);
        }
        
        return response()->json(['success' => true], 200);
    }

    // orders paid webhook
    public function ordersPaid($data, $domain)
    {
        Log::info('Order Paid:', ['order' => $data, 'domain' => $domain]);
        return 200;
    }

    // carts update webhook
    public function cartsUpdate($data, $domain)
    {
        Log::info('Cart Update:', ['cart' => $data, 'domain' => $domain]);
        return 200;
    }
}
