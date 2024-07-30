<?php

namespace App\Listeners;

use App\Actions\StripeActions\HandleCheckoutSessionCompleted;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Cache;

class StripePaymentEventListener
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
     * Use Cache to ensure there are no duplication on processing web hooks
     */
    public function handle(WebhookReceived $event): void
    {
        if($event->payload['type']=='checkout.session.completed'){

            $webhookId = $event->payload['data']['object']['id'];
            if (Cache::has("webhook-processed-{$webhookId}")) {
                return;
            }
            Cache::put("webhook-processed-{$webhookId}", true);


            (new HandleCheckoutSessionCompleted())->handle($event->payload['data']['object']['id']);
        }

    }
}
