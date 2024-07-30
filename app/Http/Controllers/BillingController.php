<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Osiset\ShopifyApp\Storage\Models\Plan;

class BillingController extends Controller
{
    public function appBilling(Request $request)
    {

        // "type" => "RECURRING",
        // "name" => "Starter",
        // "price" => 9.99,
        // "interval" => "EVERY_30_DAYS",
        // "capped_amount" => 20,
        // "terms" => "No extra charges are applied. You'll be notified once you reach the monthly review request email limit and you can decide whether to upgrade.",
        // "test" => true,
        // "trial_days" => 7,
        // "on_install" => false,

        $domain = $request->shop;
        $plans = Plan::where('domain', $domain)->get();

        $price = rand(100, 500);

        if ($plans->count() >= 0) {
            $newPlan = new Plan();
            $newPlan->type = 'RECURRING';
            $newPlan->name = 'Dynamic Plan';
            $newPlan->price = $price;
            $newPlan->interval = 'EVERY_30_DAYS';
            $newPlan->capped_amount = 500;
            $newPlan->terms = 'No extra charges are applied. You\'ll be notified once you reach the monthly review request email limit and you can decide whether to upgrade.';
            $newPlan->trial_days = 7;
            $newPlan->test = true;
            $newPlan->on_install = false;
            $newPlan->domain = $domain;
            $newPlan->save();
        }

        $plans = Plan::where('domain', $domain)->get();
        return view('plan-and-pricing.plan', compact('plans'));
    }
}
