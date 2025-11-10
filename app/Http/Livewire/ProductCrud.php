<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCrud extends Component
{
    use WithFileUploads;
    public $products, $name, $image, $price, $product_id, $revenue_type;
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
        $this->revenue_type = '';
    }

    public function store()
    {
        if ($this->isEdit) {
            $this->validate([
                'name' => 'required',
                'price' => 'required',
                'revenue_type' => 'required|in:normal,special',
            ]);
        } else {
            $this->validate([
                'name' => 'required',
                'image' => 'required|image|max:1024',
                'price' => 'required',
                'revenue_type' => 'required|in:normal,special',
            ]);
        }
        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'order' => 0,
            'revenue_type' => $this->revenue_type
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
        $this->revenue_type = $product->revenue_type;
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
