<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Livewire\Component;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class Pos extends Component
{
    public $products, $cart, $total;
    public $isModalOpen = 0;
    public $customers;
    public $customer = 0;
    public $customer_name = 'Khách vãng lai';
    public $customer_phone = '';

    public function render()
    {
        $this->products = Product::all();
        $this->customers = Customer::all();
        $cart = session('cart', []);
        $this->total = 0;
        if (count($cart) > 0) {
            foreach ($cart as $index => $item) {
                $product = Product::where('id', $index)->first();
                if (!$product) {
                    unset($cart[$index]);
                } else {
                    $this->total += $cart[$index]['price'] * $cart[$index]['quantity'];
                }
            }
        }
        $this->cart = $cart;
        if(session('customer')){
            $this->customer = session('customer');
            if($this->customer != 0){
                $cus = Customer::where('id', $this->customer)->first();
                $this->customer_name = $cus->name;
                $this->customer_phone = $cus->phone;
            }
        }
        return view('livewire.pos')->layout('layouts.pos');
    }

    public function addToCart($product_id)
    {
        $cart = $this->cart;
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += 1;
        } else {
            $product = Product::findOrFail($product_id);
            $cart[$product_id]['quantity'] = 1;
            $cart[$product_id]['name'] = $product->name;
            $cart[$product_id]['image'] = $product->image;
            $cart[$product_id]['price'] = $product->price;
        }
        session()->put('cart', $cart);
    }

    public function addQty($product_id, $quantity)
    {
        $cart = $this->cart;
        $product = Product::findOrFail($product_id);
        if (isset($cart[$product_id])) {
            if ($quantity == '-1') {
                $cart[$product_id]['quantity'] -= 1;
                if ($cart[$product_id]['quantity'] == 0) {
                    unset($cart[$product_id]);
                }
            } else {
                $cart[$product_id]['quantity'] += 1;
            }
        }
        session()->put('cart', $cart);
    }

    public function payment($payment_method)
    {
        if (count($this->cart) > 0) {
            $order = new Order();
            $order->total = $this->total;
            $order->payment_method = $payment_method;
            $order->save();

            $customer = new Buyer([
                'name'          => $this->customer_name,
                'phone'          => $this->customer_phone,
            ]);
    
            $invoice = Invoice::make()
                ->sequence($order->id)
                ->setCustomData(['payment_method' => $payment_method])
                ->logo(public_path('images/logo.png'))
                ->buyer($customer);
            foreach ($this->cart as $index => $item){
                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->product_id = $index;
                $order_detail->quantity = $item['quantity'];
                $order_detail->price = $item['price'];
                $order_detail->save();
                $invoice->addItem((new InvoiceItem())->title($item['name'])->pricePerUnit($item['price']));
            }
            
            session()->forget('cart');
            session()->forget('customer');
            $this->customer = 0;
            return response()->streamDownload(function () use($invoice) {
                echo  $invoice->stream();
            }, 'invoice.pdf');
        }
    }

    public function addCustomer(){
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    public function cusChange(){
        session()->put('customer', $this->customer);
    }

    public function choose()
    {
        $this->isModalOpen = false;
    }
}
