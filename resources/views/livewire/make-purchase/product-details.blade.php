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

        <form wire:submit.prevent="buy" id="productQuantity">
            <div class="mt-4 space-y-4">
                <input id="quantity" type="number" wire:model.live="quantity" min="1" max="10000" placeholder="Enter quantity" autofocus class="block w-full rounded-md border-0 py-1.15 pl-3 pr-10 text-gray-800"/>
            </div>
            @error('quantity')
            <div class="mt-2 text-red-600">{{$message}}</div>
            @enderror
            <div class="px-2 mt-6 grid grid-cols-2 gap-1">
                <x-button class="button-success col-span-1"> Buy(Once Off) </x-button>
                <x-button class="button-success col-span-1" wire:click="deposit"> Deposit(50% then Auto Full Payment in 5 minutes) </x-button>
            </div>
        </form>
    </div>
    @if($isModalOpen)
        @include('livewire.make-purchase.buyer-email')
    @endif
</div>
