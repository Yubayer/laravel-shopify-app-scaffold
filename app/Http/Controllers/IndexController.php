<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\WebhookInstallJob;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $authShop = $request->user();

        dump(env('SHOPIFY_API_SECRET'));

        // dispatch WebhookInstallJob
        // WebhookInstallJob::dispatch($authShop);

        $webhooks = $authShop->api()->rest('GET', '/admin/api/2024-04/webhooks.json')['body']['webhooks'];

        dump($webhooks);
        return view('welcome');
    }

}
