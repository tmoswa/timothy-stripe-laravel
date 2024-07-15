<?php

namespace App\Console\Commands;

use App\Mail\FullPaymentConfirmation;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Money\Money;

class FullPaymentTaken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:full-payment-taken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command every minute';

    /**
     * Execute the console command.
     * This method is run every minute based on configurations in console.php
     */
    public function handle(bool $testMode=false)
    {
        $depositedOrders = Order::whereTime('created_at', '<', now()->subMinutes(5))->whereColumn('amount_total', '>', 'amount_paid')->get();
        foreach ($depositedOrders as $myOrder) {
            try {
                $balanceAmount = Money::USD($myOrder->amount_total->getAmount() - $myOrder->amount_paid->getAmount());
                $myData = $myOrder->customer->charge((integer)$balanceAmount->getAmount(), $myOrder->customer->paymentMethods(0)[0]->id, [
                    'return_url' => route('home'), // Replace with the route to your payment completion page
                ]);
                if ($myData->status == "succeeded") {
                    Order::updateOrCreate(['id' => $myOrder->id], [
                        'amount_paid' => (integer)($myOrder->amount_paid->getAmount() + $myData->amount),
                    ]);
                    Mail::to($myOrder->customer)->send(new FullPaymentConfirmation($myOrder));
                }
            } catch (IncompletePayment $exception) {
                if ($exception->payment->requiresPaymentMethod()) {
                    Log::info("Payment Requires Method");
                } elseif ($exception->payment->requiresConfirmation()) {
                    Log::info("Payment require Confirmation");
                } else {
                    // Get the payment intent status...
                    Log::info("Payment status" . $exception->payment->status);
                }
            }

        }
        return ($testMode)?"success" :"";
    }
}
