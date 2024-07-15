<div class="mt-5">
<div>
        <x-input type="text" wire:model.live="searchContent" class="w-full" placeholder="Search..."></x-input>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 md:gap-4 mt-12">
    @foreach($this->product as $product)
        <x-panel class="relative">
            <a href="{{route('product',$product)}}" class="absolute insert-o w-full h-full"></a>
            <img src="{{$product->image->path}}" alt="product">
            <h2 class="font-medium text-lg"> {{$product->name}} </h2>
            <span class="text-gray-700 text-sm">{{ $product->price }}</span>
        </x-panel>
    @endforeach

</div>
    {{ $this->product->links() }}
</div>
