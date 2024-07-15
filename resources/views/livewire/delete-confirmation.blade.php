<div class=" {{$isDeleteClicked?'opacity-100':'opacity-0'}} flex flex-col space-y-4 min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-gray-500 bg-opacity-75" role="dialog" aria-modal="true">

    <div class="flex flex-col p-8 bg-gray-800 shadow-md hover:shodow-lg rounded-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-16 h-16 rounded-2xl p-3 border border-gray-800 text-blue-400 bg-gray-900" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex flex-col ml-3">
                    <div class="font-medium leading-none text-gray-100"> {{$deleteTitle}} ?</div>
                    <p class="text-sm text-gray-500 leading-none mt-1">Note that by deleting this you will lose the data
                    </p>
                </div>
            </div>
            <button wire:click.prevent="deleteDetail({{ $deleteId }})" class="flex-no-shrink bg-red-500 px-5 ml-4 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 border-red-500 text-white rounded-full">Delete</button>
            <button wire:click="closeModal()" class="flex-no-shrink bg-indigo-600 px-3 ml-2 py-1 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider  text-white rounded-full">Cancel</button>
        </div>
    </div>
</div>


