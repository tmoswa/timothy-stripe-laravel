<?php

namespace App\Livewire;

use App\Models\Image;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;


class ProductDashBoard extends Component
{
    use WithPagination, WithFileUploads;

    public $id;
    public $productDetail;
    public $searchContent = '';
    public $isModalOpen = 0;
    public $name, $price, $description;
    public $paginate=1;

    public $photos;
    public $existingPhotos;
    public $uploading = false;

    /*
     * There are 2 types of deleting happening on one page so we need a bit of variables
     * ToDo
     * Optimize and reduce these variables
     */
    public $isDeleteClicked = false;
    public $deleteProduct = false;
    public $deleteImage = false;
    public $photoDelete;
    public $deleteTitle = '';
    public $deleteId;

    protected $rules = [
        'name' => 'required|min:2',
        'price' => 'required|numeric|min:0.1|max:1000000000',
        'description' => 'required|min:2',
    ];

    public function getProductProperty()
    {
        return Product::where('name', 'like', '%' . $this->searchContent . '%')->paginate($this->paginate);
    }

    public function render()
    {
        return view('livewire.manage-product.product-dash-board');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->photos = null;
        $this->id = null;
        $this->existingPhotos=null;
        $this->photoDelete=null;
    }

    public function saveData()
    {
        $this->validate();
        if ($this->photos == null && $this->id == null) {
            flash()->error('Please add product Image(s) to proceed!');
            return;
        }
        $this->uploading = true;
        $product = Product::updateOrCreate(['id' => $this->id], [
            'name' => $this->name,
            'price' => ($this->price*100),
            'description' => $this->description,
        ]);
        if ($this->photos != null) {
            foreach ($this->photos as $image) {
                $randomize = rand(111111, 999999);
                $image->storeAs(path: 'media', name: $randomize . $image->getClientOriginalName());
                $imageModel = new Image;
                $imageModel->path = 'media/' . $randomize . $image->getClientOriginalName();
                $imageModel->product_id = $product->id;
                $imageModel->featured = 0;
                $imageModel->save();
            };
        }
        $this->uploading = false;
        flash()->success('Saved Successfully.');
        $this->closeModalPopover();
    }

    public function edit(Product $product)
    {
        $this->id = $product->id;
        $this->name = $product->name;
        $this->price = $product->price->getAmount()/100;
        $this->description = $product->description;
        $this->existingPhotos = $product->images;
        $this->productDetail = $product;
        $this->photos = null;
        $this->openModalPopover();
    }

    public function removePhoto(Image $image)
    {
        $product = Product::find($this->productDetail->id);
        if ($product->images->count() == 1) {
            flash()->error('please add other product images, this is the last one!');
            $this->closeModal();
            return;
        }
        $singlePath = Image::find($image->id);

        if (file_exists(public_path($singlePath->path))) {
            unlink(public_path($singlePath->path));
        } else {
            flash()->error('You are trying to remove a non-existent photo!');
            $this->closeModal();
        }
        $singlePath->delete();
        $product = Product::find($this->productDetail->id);
        $this->existingPhotos = $product->images;
        $this->closeModal();
        flash()->success('Your Photo has been removed!');
    }

    public function deleteProduct($id)
    {
        $images = Product::find($id)->images();
        foreach ($images as $image) {
            $this->removePhoto($image);
        }
        Product::find($id)->images()->delete();
        Product::find($id)->delete();
        $this->closeModal();
        flash()->success('Successfully deleted.');
        return redirect()->route('dashboard');
    }

    /*
     * Because of Time i am leaving this here
     * ToDo
     * Make a single method
     */
    public function deleteProductInit($id)
    {
        $this->deleteId = $id;
        $this->deleteTitle = 'Delete Product';
        $this->isDeleteClicked = true;
        $this->deleteProduct = true;
        $this->deleteImage = false;
    }

    public function deletePhotoInit(Image $image)
    {
        $this->photoDelete = $image;
        $this->deleteTitle = 'Delete Photo';
        $this->isDeleteClicked = true;
        $this->deleteProduct = false;
        $this->deleteImage = true;
    }
    public function deleteDetail()
    {
        ($this->deleteProduct) ?
            $this->deleteProduct($this->deleteId) :
            $this->removePhoto($this->photoDelete);
    }


    public function closeModal()
    {
        $this->photoDelete = null;
        $this->deleteTitle = '';
        $this->isDeleteClicked = false;
        $this->deleteProduct = false;
        $this->deleteImage = false;
        $this->deleteId = null;
    }

}
