<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center  min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75">
            </div>
        </div>
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-show shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="mt-6 text-gray-500 font-bold text-center ">
                @if($this->id==null)
                    Add Product
                @else
                    Update Product
                @endif
                <hr>
            </div>
            <form enctype="multipart/form-data" id="addProduct" name="addProduct">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mt-4">
                            <x-label for="name" value="{{ __('Name:') }}"/>
                            <x-input id="name" name="name" class="block mt-1 w-full" type="text" required
                                     wire:model="name"/>
                            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-2">
                            <x-label for="price" value="{{ __('Price(in cents):') }}"/>
                            <x-input id="price" type="number" min="100" max="1000000000000" name="price"
                                     class="block mt-1 w-full" required
                                     wire:model="price"/>
                            @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4 grid grid-cols-1">
                            <x-label for="description" value="{{ __('Description:') }}"/>
                            <x-input id="description" name="description" class="block mt-1 w-full" type="text" required
                                     wire:model="description"/>
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4 grid grid-cols-1">
                            <div class="grid grid-cols-3">
                                @if(is_object($this->existingPhotos))
                                    @foreach($this->existingPhotos as $eXhoto)
                                        <div class="">
                                            <span class="text-red-500 cursor-pointer float-right mr-16"
                                                  wire:click="deletePhotoInit({{$eXhoto}})">x</span>
                                            <img src="/{{ $eXhoto->path }}" style="height: 90px;"/>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <label for="photos" name="">Choose Images</label>
                            <input type="file" wire:model="photos" id="photos" multiple accept="image/*">
                            @error('photos.*') <span class="error text-red-600">{{ $message }}</span> @enderror
                            <!-- Image loader -->
                            <div wire:loading wire:target="photos">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Uploading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <button wire:click.prevent="saveData()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
            @if($this->id==null)
                        Save
                    @else
                        Update
                    @endif

          </button>
        </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
          <button wire:click="closeModalPopover()" type="button"
                  class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
            Cancel
          </button>
        </span>
                </div>
            </form>

        </div>


    </div>

</div>

</div>
