<?php
namespace App\Traits;

use App\order;
use App\Shipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait Payment{
    public function data($request){
        $data = [];
        $data['items'] = [
            [
                'name' => 'Product 1',
                'product_id' => '20',
                'price' => 99.99,
                'qty' => 1
            ],
            [
                'name' => 'Product 2',
                'product_id' => '30',
                'price' => 30.99,
                'qty' => 9
            ]
        ];
        $data['customer'] = [
            [
                'customer_name' => 'Customer Name',
                'customer_id' => Auth::user()->id,
                'customer_email' => 1,
                'customer_phone' => 1,
                'customer_address' => 1,
                'customer_city' => 1,
                'customer_state' => 1,
                'customer_zip' => 1,
                'customer_country' => 1,
                'cus_fax' => '',
            ]
        ];
        $data['shipt'] = [
            [
                'ship_name' => 'Customer Name',
                'ship_email' => 'shiptmail@gmail.com',
                'ship_phone' => 'shiptmail@gmail.com',
                'ship_address' => 'ship address will be go here',
                'ship_city' => 'ship city',
                'ship_state' => 'ship state',
                'ship_zip' => '1000',
                'ship_country' => 'india',
            ]
        ];
        $data['invoice_id'] = Auth::user()->id.uniqid('00');
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['currency'] = 'USD';
        $data['payment_gateway'] = $request['payment_gateway'];
        $total = 0;
        foreach($data['items'] as $item) {
            $total += $item['price']*$item['qty'];
        }
        $data['total'] = $total;
        $data['shipping_discount'] = round((10 / 100) * $total, 2);
        $orderSave = $this->saveOrder($data);
        if($orderSave = true){
            return $data;
        }
    }

    protected function saveOrder($data){
        for($i=0;$i<count($data['items']);$i++){
            $order = new order();
            $order->customer_id = $data['customer'][0]['customer_id'];
            $order->product_id = $data['items'][$i]['product_id'];
            $order->product_name = $data['items'][$i]['name'];
            $order->quantity = $data['items'][$i]['qty'];
            $order->total = $data['total'];
            $order->order_status = 'pending';
            $order->invoice_id = $data['invoice_id'];
            $order->currency = $data['currency'];
            $order->payment_status = 'pending';
            $order->payment_gateway = $data['payment_gateway'];
            $order->save();
        }
        $saveShip = $this->saveShip($data);
        if($saveShip = true){
            return true;
        }
    }

    protected function saveShip($data){
        $ship = new Shipt();
        $ship->customer_id = $data['customer'][0]['customer_id'];
        $ship->name = $data['shipt'][0]['ship_name'];
        $ship->email = $data['shipt'][0]['ship_email'];
        $ship->phone = $data['shipt'][0]['ship_phone'];
        $ship->address = $data['shipt'][0]['ship_address'];
        $ship->city = $data['shipt'][0]['ship_city'];
        $ship->zip = $data['shipt'][0]['ship_zip'];
        $ship->order_status = 'pending';
        $ship->save();
        return true;
    }

    public function updateInfoAfterPayment($response){
        DB::table('orders')
            ->where('invoice_id',$response['INVNUM'])
            ->update([
                'order_status'=>'ordered',
                'payment_status'=>'paid'
            ]);
        return true;
    }
}