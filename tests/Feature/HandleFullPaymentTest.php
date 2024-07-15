<?php

namespace Tests\Feature;

use App\Console\Commands\FullPaymentTaken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleFullPaymentTest extends TestCase
{
    use RefreshDatabase;

    private $process;

    protected function setUp(): void
    {
        parent::setUp();
        $this->process = new FullPaymentTaken();
    }

    public function test_full_payment_taken(): void
    {
        $data=$this->process->handle(true);
        $this->assertEquals($data,"success");
    }
}
