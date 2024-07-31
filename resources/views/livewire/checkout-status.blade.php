<div class="bg-white w-full rounded shadow mt-12 p-5 mx-w-xl mx-auto grid grid-col-2">
    @if($this->order)
<p>
    Thank you for your order,
</p>
        <a href="{{ route('home') }}" class="py-2 px-4 bg-green-600 rounded text-white w-40 float-right">Shop Again</a>
    @else
    <p wire:poll>
        Waiting for payment confirmation please stand by
    </p>
        @endif

</div>
