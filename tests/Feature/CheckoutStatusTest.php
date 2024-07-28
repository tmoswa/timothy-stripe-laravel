<?php

namespace Tests\Feature;

use App\Livewire\CheckoutStatus;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutStatusTest extends TestCase
{
    public function test_checkout_status()
    {
        Livewire::test(CheckoutStatus::class)
            ->assertSee('7')
            ->assertDontSee('7777');
    }


}
