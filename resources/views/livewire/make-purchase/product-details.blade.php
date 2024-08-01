<div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-12 my-12">
    <div class="space-y-4" x-data="{ image: '/{{$this->product->image->path}}'}">
        <div class="bg-white p-5 rounded-lg shadow">
            <img x-bind:src="image" alt="">
        </div>
        <div class="grid grid-cols-4 gap-4">
            @foreach($this->product->images as $image)
                <div class="rounded bg-white p-2 rounded shadow">
                    <img src="/{{ $image->path }}" @click="image= '/{{ $image->path }}'" alt="">
                </div>
            @endforeach
        </div>
    </div>
    <div>
        <h1 class="text-3xl font-medium">{{$this->product->name}}</h1>
        <div class="text-xl text-gray-700">{{$this->product->price}}</div>

        <div class="mt-4">
            {{$this->product->description}}
        </div>

        <form wire:submit.prevent="buy" class="px-2" id="productQuantity">
            <div class="mt-4 space-y-4">
                <input id="quantity" type="number" wire:model.live="quantity" min="1" max="10000" placeholder="Enter quantity" autofocus class="block w-full rounded-md border-0 py-1.15 pl-3 pr-10 text-gray-800"/>
            </div>
            @error('quantity')
            <div class="mt-2 text-red-600">{{$message}}</div>
            @enderror
            <div class="px-2 mt-6 grid grid-cols-1 gap-1">
                <span class="font-bold ">Payment Type:</span>
                <lable class="col-span-1">
                    <input type="radio" wire:model="paymentType" value="fullPayment"/>
                    Once-off Payment
                </lable>
                <lable class="col-span-1">
                    <input type="radio" wire:model="paymentType" value="deposit"/>
                    Deposit 50% - balance will automatically be processed in 5 minutes
                </lable>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-1">
                <div wire:click="addToCart" class="cursor-pointer inline-flex items-center px-4 py-2 bg-[#041332] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    Add to cart
                </div>
                <x-button class="button-success col-span-1"> Checkout This Product </x-button>
            </div>
        </form>

    </div>
    @if($isModalOpen)
        @include('livewire.make-purchase.buyer-email')
    @endif
</div>
