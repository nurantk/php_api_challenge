<?php

namespace App\Jobs;

use App\Models\Device;
use App\Http\Services\PurchaseServiceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubscriptionCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;
    protected $purchaseManager;
    protected $device;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription=$subscription;
        $this->device=Device::find($subscription->uId);
        $this->purchaseManager=new PurchaseServiceManager();
        self::onQueue('check-purchase-worker');

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $response=$this->purchaseManager->checkApi($this->device->operatingSystem,$this->subscription->receiptId);
            if($response->success){
                if(!$response->status){
                    $this->subscription->status=0;
                    $this->subscription->save();
                }
            }
        }catch (\Exception $exception){
            Log::error("Job error:".$exception->getMessage());
        }


    }
}
