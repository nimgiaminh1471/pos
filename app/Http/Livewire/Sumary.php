<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Sumary extends Component
{
    public $date, $topCustomer, $topProduct, $total_special_revenue;
    
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

        $this->total_special_revenue = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
            ->whereMonth('order_details.created_at', $date[1])
            ->whereYear('order_details.created_at', $date[0])
            ->where('order_details.product_id', '<>', 0)
            ->where('products.revenue_type', 'special')
            ->sum(DB::raw('order_details.price * order_details.quantity'));
    }
}
