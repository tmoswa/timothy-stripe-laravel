<x-slot name="header" class="h-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center w-full">
            {{ __('Manage Products') }}
        </h2>
</x-slot>

    <div class="py-1">
        <div class="max-w-11xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container">
                    <div class="row">

                        <div class="col-md-12">
                            @if($isModalOpen)
                                @include('livewire.manage-product.create')
                            @endif
                                @if($isDeleteClicked)
                                    @include('livewire.delete-confirmation')
                                @endif
                            <div class="grid grid-cols-5 gap-4 h-10">
                                <x-input type="text" id="searchProducts" name="searchProducts" wire:model.live="searchContent" class="col-span-4 ml-1 mt-1" placeholder="Search..."></x-input>
                                <x-button wire:click="create()"
                                          class="mr-1 mt-1 inline-flex justify-center rounded-md border border-transparent bg-gray-800 text-base font-bold text-white shadow-sm hover:bg-blue-700 float-right">
                                    Add Product
                                </x-button>
                            </div>

                                @foreach($this->product as $product)

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-12 my-12">
                                        <div class="space-y-4" x-data="{ image{{$product->id}}: '/{{$product->image->path}}'}">
                                            <div class="bg-white p-5 rounded-lg shadow">
                                                <img x-bind:src="image{{$product->id}}" alt="" class="h-40">
                                            </div>
                                            <div class="grid grid-cols-4 gap-4">
                                                    @foreach($product->images as $image)
                                                        <div class="rounded bg-white p-2 rounded shadow">
                                                            <img src="/{{ $image->path }}" @click="image{{$product->id}}= '/{{ $image->path }}'" alt="">
                                                        </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                        <div>
                                            <h1 class="text-3xl font-medium">{{$product->name}}</h1>
                                            <div class="text-xl text-gray-700">{{$product->price}}</div>

                                            <div class="mt-4">
                                                {{$product->description}}
                                                <hr>
                                                <div class="mt-2">
                                                    <x-button wire:click="edit({{$product}})">Edit</x-button>
                                                    <x-button
                                                              wire:click="deleteProductInit({{$product->id}})"
                                                              class="text-white bg-red-700 rounded-full flex items-center justify-center">
                                                        Delete
                                                    </x-button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <hr>
                                @endforeach
                            {{ $this->product->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>



