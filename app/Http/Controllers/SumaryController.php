<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SumaryController extends Controller
{
    //
    public function summary(){
        $topCustomer = Order::with('customer')->addSelect(DB::raw('SUM(total) as purchase_total, customer_id'))->where('customer_id', '<>' , 0)
        ->groupBy('customer_id')->take(10)
        ->orderBy('purchase_total', 'DESC')->get();
        $customer['label'] = [];
        $customer['data'] = [];
        foreach ($topCustomer as $key => $value) {
            array_push($customer['label'], $value->customer->name);
        }

        foreach ($topCustomer as $key => $value) {
            array_push($customer['data'], $value->purchase_total);
        }

        $topProduct = OrderDetail::with('product')->addSelect(DB::raw('SUM(quantity) as total, product_id'))->whereMonth('created_at', date('m'))
        ->groupBy('product_id')->take(10)
        ->orderBy('total', 'DESC')->get();
        $product['label'] = [];
        $product['data'] = [];
        foreach ($topProduct as $key => $value) {
            array_push($product['label'], $value->product->name);
        }

        foreach ($topProduct as $key => $value) {
            array_push($product['data'], $value->total);
        }

        return view('summary.summary', compact('customer', 'product'));
    }
}
