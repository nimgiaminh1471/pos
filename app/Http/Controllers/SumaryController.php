<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SumaryController extends Controller
{
    //
    public function summary(Request $request){
        if(isset($request->date)){
            $date = $request->date;
        }else{
            $date = now()->year . '-' . now()->month;
        }
        $dates = explode("-",$date);
        $topCustomer = Order::with('customer')->addSelect(DB::raw('SUM(total) as purchase_total, customer_id', 'name'))->whereMonth('created_at', $dates[1])->whereYear('created_at', $dates[0])->where('customer_id', '<>' , 0)
        ->groupBy('customer_id')->take(10)
        ->orderBy('purchase_total', 'DESC')->get();
        // dd($topCustomer);
        
        $topProduct = OrderDetail::with('product')->addSelect(DB::raw('SUM(quantity) as total, product_id'))->whereMonth('created_at', $dates[1])->whereYear('created_at', $dates[0])
        ->groupBy('product_id')->take(10)
        ->orderBy('total', 'DESC')->get();

        $total = Order::whereMonth('created_at', $dates[1])->whereYear('created_at', $dates[0])->get()->sum('total');
        $total_products = OrderDetail::whereMonth('created_at', $dates[1])->whereYear('created_at', $dates[0])->get()->sum('quantity');
        $total_customers = Customer::whereMonth('created_at', $dates[1])->whereYear('created_at', $dates[0])->get()->count();

        return view('sumary.sumary', compact('topCustomer', 'topProduct', 'date', 'total', 'total_products', 'total_customers'));
    }
}
