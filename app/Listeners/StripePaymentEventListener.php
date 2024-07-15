<?php

namespace App\Listeners;

use App\Actions\StripeActions\HandleCheckoutSessionCompleted;
use Laravel\Cashier\Events\WebhookReceived;

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
     */
    public function handle(WebhookReceived $event): void
    {
        if($event->payload['type']=='checkout.session.completed'){
            (new HandleCheckoutSessionCompleted())->handle($event->payload['data']['object']['id']);
        }
    }
}
