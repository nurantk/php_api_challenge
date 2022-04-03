<?php

namespace App\Listeners;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSubscriptionNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $device = $event->subscription->device;
        $url = $device->appUrl;

        try {
            Http::retry(5, 100)->post($url, [
                'deviceId' => $device->uId,
                'appId' => $device->appId,
                'event' => $event->eventName
            ]);
        } catch (ConnectException $exception) {
            Log::error('Device: '.$device->uId.' - '. $event->eventName.' - '.$exception->getMessage());
        }
    }
}
