<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderCrud extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.order-crud');
    }

    public function delete($id)
    {
        Order::find($id)->delete();
        session()->flash('message', 'Order deleted.');
    }
}
