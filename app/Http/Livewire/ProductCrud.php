<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCrud extends Component
{
    use WithFileUploads;
    public $products, $name, $image, $price, $product_id;
    public $isModalOpen = 0;
    public $isEdit = false;
    public function render()
    {
        $this->products = Product::orderBy('order')->get();
        return view('livewire.product-crud');
    }
    public function create()
    {
        $this->isEdit = false;
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
        $this->image = '';
        $this->price = '';
    }

    public function store()
    {
        if ($this->isEdit) {
            $this->validate([
                'name' => 'required',
                'price' => 'required',
            ]);
        } else {
            $this->validate([
                'name' => 'required',
                'image' => 'required|image|max:1024',
                'price' => 'required',
            ]);
        }
        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'order' => 0
        ];

        if ($this->image && !is_string($this->image)) {
            $image = $this->image->store('photos', 'public');
            $data['image'] = $image;
        }
        if ($this->isEdit) {
            Product::updateOrCreate(['id' => $this->product_id], $data);
            session()->flash('message', $this->product_id ? 'Product updated.' : 'Product created.');
            $this->closeModalPopover();
            $this->resetCreateForm();
        } else {
            Product::create($data);
            session()->flash('message', $this->product_id ? 'Product updated.' : 'Product created.');
            $this->closeModalPopover();
            $this->resetCreateForm();
        }
    }
    public function edit($id)
    {
        $this->isEdit = true;
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->image = $product->image;
        $this->price = $product->price;

        $this->openModalPopover();
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('message', 'Product deleted.');
    }

    public function updateProductOrder($data)
    {
        foreach($data as $product){
            $productData = Product::find($product['value']);
            if($productData){
                $productData->order = $product['order'];
                $productData->save();
            }
        }
    }
}
