<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Welcome extends Component
{
    public $searchContent = '';
    public function getProductProperty()
    {
        return Product::query()->where('name', 'like', '%' . $this->searchContent . '%')->paginate(8);
    }
    public function render()
    {
        return view('livewire.shop-catalog');
    }
}
