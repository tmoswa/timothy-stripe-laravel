<?php

namespace App\Providers;

use App\Events\EventServiceProvider;
use App\Events\ManualEventServiceProvider;
use App\Listeners\StripeEventListener;
use App\Listeners\StripePaymentEventListener;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Fortify\Fortify;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;


use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
       Cashier::calculateTaxes();
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            if ($user &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
        Blade::stringable(function(Money $money)
        {
            $currencies = new ISOCurrencies();
            $numberFormatter = new \NumberFormatter('en_US',\NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter,$currencies);

            return $moneyFormatter->format($money);
        });

        Event::listen(
            WebhookReceived::class,
            StripePaymentEventListener::class,
        );
    }
}
