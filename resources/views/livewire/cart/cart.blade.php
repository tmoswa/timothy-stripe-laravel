<div class="grid grid-cols-1 md:grid-cols-6 md:gap-4 mt-12">

    <x-panel class="col-span-1 md:col-span-4">
        <table class="w-full">
            <thead>
            <tr class="bg-[#041332] text-white">
                <th class="text-left">Product</th>
                <th class="text-left">Unit Amount</th>
                <th class="text-left">Quantity</th>
                <th class="text-right">Total Amount</th>
                <th></th>
            </tr>
            <hr/>
            </thead>
            <tbody>
            @foreach($this->items as $item)
                <tr>
                    <td>{{$item->product->name}}</td>
                    <td class="">
                        {{$item->product->price}}
                    </td>
                    <td class="flex items-center">
                        <button wire:click="decrement({{$item->id}})" @disabled($item->quantity<2)>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <div class="ml-2 mr-2">{{$item->quantity}}</div>
                        <button wire:click="increment({{$item->id}})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </td>
                    <td class="text-right">
                        {{$item->subTotal}}
                    </td>
                    <td class="pl-2">
                        <button wire:click="delete({{$item->id}})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-600 ">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </button>
                    </td>
                </tr>


            @endforeach
            </tbody>
            <tfoot>
            <hr/>
            <tr>
                <td colspan="3" class="text-right font-medium col-span-4">Total</td>
                <td class="font-medium text-right">{{$this->cart->total}}</td>
                <td></td>
            </tr>
            </tfoot>
        </table>


    </x-panel>

    <x-panel class="md:col-span-2">
        @guest
            @if($this->cart->total->getAmount()>0)
                <x-button wire:click="buy"  class="w-full justify-center"> Checkout</x-button>
            @endif
            <a href="{{ route('home') }}" class="w-full py-2 px-4 bg-green-600 rounded text-white float-right text-center  mt-3">Continue Shop</a>

        @endguest
        @auth

        @endauth

    </x-panel>
    @if($isModalOpen)
        @include('livewire.make-purchase.buyer-email')
    @endif
</div>
