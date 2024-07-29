<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//use log
use Illuminate\Support\Facades\Log;

// user model
use App\Models\User;

class AppUninstalledWebhookJob implements ShouldQueue
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
            // update password null
            $update = $this->shop->update(['password' => '']);
            if(!$update) {
                throw new \Exception('Password update failed');
                Log::error('Password update failed for domain: ' . $this->shop->name);
            } else {
                Log::info('Password update success for domain: ' . $this->shop->name);
            }
        } catch (\Exception $e) {
            Log::error('AppUninstalledWebhookJob: ---- failed catch', ['error' => $e->getMessage()]);
        }
    }
}
