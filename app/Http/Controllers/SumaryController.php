<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use DateTime;
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


        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $dataMonths = [];
        foreach($months as $month){
            $customerChartMonth = Order::with('customer')->whereMonth('created_at', $month)->whereYear('created_at', $dates[0])->sum('total');
            $dataMonths['Tháng ' . $month] = $customerChartMonth;
        }

        $days = $this->getDayArray($dates[1], $dates[0]);

        $dataDays = [];
        foreach($days as $day){
            $customerChartDay = Order::with('customer')->whereDate('created_at', date($dates[0] . "-" . $dates[1] . "-" . $day))->sum('total');
            $dataDays['Ngày ' . $day] = $customerChartDay;
        }

        $total_special_revenue = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
        ->whereMonth('order_details.created_at', $dates[1])
        ->whereYear('order_details.created_at', $dates[0])
        ->where('order_details.product_id', '<>', 0)
        ->where('products.revenue_type', 'special')
        ->sum(DB::raw('order_details.price * order_details.quantity'));

        return view('sumary.sumary', compact('topCustomer', 'topProduct', 'date', 'total', 'total_products', 'total_customers', 'dataMonths', 'dataDays', 'total_special_revenue'));
    }

    public function getDayArray($month, $year){
        $date = $year . "-". $month ."-1";
        $date = new DateTime($date);
        $date->modify('last day of');

        $last = $date->format('d');
        $day = [];
        for($i = 1; $i <= $last; $i++){
            array_push($day, $i);
        }
        return $day;
    }
}
