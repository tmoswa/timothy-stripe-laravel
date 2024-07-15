@component('mail::message')
<main>
<h1>Order Confirmation
@if($order->amount_total->getAmount()!=$order->amount_paid->getAmount())
    - Deposit
@else
    - Full Payment
@endif
</h1>
<p>Good Day {{$order->customer->name}}</p>

<p>Thank you for your order below:</p>
<table style="width: 100%">
        <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{$item->name}} </td>
                <td>{{$item->description}} </td>
                <td>{{$item->price}} </td>
                <td>{{$item->quantity}} </td>
                <td>{{$item->amount_tax}} </td>
                <td>{{$item->amount_total}} </td>
            </tr>
        @endforeach
        </tbody>
 <tfooter>
                @if($order->amount_discount->isPositive())
                    <tr>
                        <td colspan="5" style="text-align: right">Discount Amount:</td>
                        <td>{{$order->amount_discount}}</td>
                    </tr>
                @endif
                @if($order->amount_tax->isPositive())
                    <tr>
                        <td colspan="5" style="text-align: right">Tax Amount:</td>
                        <td>{{$order->amount_tax}}</td>
                    </tr>
                @endif
                @if($order->amount_subtotal->isPositive())
                    <tr>
                        <td  colspan="5" style="text-align: right">Sub Total:</td>
                        <td>{{$order->amount_subtotal}}</td>
                    </tr>
                @endif
                @if($order->amount_total->isPositive())
                    <tr>
                        <td colspan="5" style="text-align: right"> Total Invoice:</td>
                        <td>{{$order->amount_total}}</td>
                    </tr>
                @endif
  @if($order->amount_paid->isPositive())
  <tr>
      <td colspan="5" style="text-align: right"> Amount Paid: </td>
      <td>{{$order->amount_paid}}</td>
  </tr>
  <tr>
      <td colspan="5" style="text-align: right"><hr> Balance Amount: <hr></td>
      <td class="font-bold"><hr>{{\Money\Money::USD($order->amount_total->getAmount()-$order->amount_paid->getAmount())}}<hr></td>
  </tr>
  @endif


 </tfooter>
</table>
    @component('mail::button',['url'=>route('home'),'color'=>'success'])
        Order Again
    @endcomponent
</main>
@endcomponent


