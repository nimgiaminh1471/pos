<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Sumary extends Component
{
    public $date, $topCustomer, $topProduct;

    public function mount() 
    {
        $this->date = now()->year . '-' . now()->month;
    }

    public function render()
    {
        return view('livewire.sumary');
    }

    public function check(){
        $date = explode("-",$this->date);
        $this->topCustomer = Order::with('customer')->addSelect(DB::raw('SUM(total) as purchase_total, customer_id', 'name'))->whereMonth('created_at', $date[1])->whereYear('created_at', $date[0])->where('customer_id', '<>' , 0)
        ->groupBy('customer_id')->take(10)
        ->orderBy('purchase_total', 'DESC')->get();
        // dd($this->topCustomer);
        
        $this->topProduct = OrderDetail::with('product')->addSelect(DB::raw('SUM(quantity) as total, product_id'))->whereMonth('created_at', $date[1])->whereYear('created_at', $date[0])
        ->groupBy('product_id')->take(10)
        ->orderBy('total', 'DESC')->get();
    }
}
