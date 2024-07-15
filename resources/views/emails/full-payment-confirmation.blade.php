@component('mail::message')
<main>
<h1>Full Payment Confirmation</h1>
<p>Hey {{$order->customer->name}}</p>

<p>We have deducted the balance on your previous order. Thank you.</p>

<p class="mt-10">Click button below to checkout:</p>

 @component('mail::button',['url'=>route('home'),'color'=>'success'])
        Order Again
 @endcomponent
</main>
@endcomponent


