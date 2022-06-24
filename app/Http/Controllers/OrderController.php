<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    //
    public function printOrder($id){
        $order = Order::findOrFail($id);
        $customer = $order->customer;
        $customer_name = "Khách vãng lai";

        if($customer){
            $customer_name = $customer->name;
            $customer_invoice = new Buyer([
                'name'          => $customer->name,
                'phone'          => $customer->phone,
                'custom_fields' => [
                    'note' => $customer->note
                ]
            ]);
        }else{
            $customer_invoice = new Buyer([
                'name'          => 'Khách vãng lai',
            ]);
        }

        $invoice = Invoice::make()
            ->sequence($order->id)
            // ->setCustomData(['payment_method' => $payment_method])
            ->logo(public_path('images/logo.png'))
            ->buyer($customer_invoice);
        $order_detail = $order->order_detail;
        foreach ($order_detail as $index => $item){
            try {
                //code...
                $invoice->addItem((new InvoiceItem())->title($item->product->name)->pricePerUnit($item->product->price)->quantity($item->quantity));
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        
        return response()->streamDownload(function () use($invoice) {
            echo  $invoice->stream();
        }, $invoice->getSerialNumber() . '_' . Str::slug($customer_name) . '.pdf');
    }
}
