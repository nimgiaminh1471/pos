<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderCrud extends Component
{
    use WithPagination;
    public $orders = [];
    public function render()
    {
        $this->orders = Order::paginate(1)->all();
        return view('livewire.order-crud',[
            'paginate' => Order::paginate(1),
            'total' => Order::get()->sum('total')
        ]);
    }

    public function delete($id)
    {
        Order::find($id)->delete();
        session()->flash('message', 'Order deleted.');
    }
}
