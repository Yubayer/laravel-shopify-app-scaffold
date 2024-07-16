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

    protected $domain;

    /**
     * Create a new job instance.
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            // update password null
            $update = User::where('name', $this->domain)->update(['password' => '']);
            if(!$update) {
                throw new \Exception('Password update failed');
                Log::error('Password update failed for domain: ' . $this->domain);
            } else {
                Log::info('Password update success for domain: ' . $this->domain);
            }
        } catch (\Exception $e) {
            Log::error('AppUninstalledWebhookJob: ---- failed catch', ['error' => $e->getMessage()]);
        }
    }
}
