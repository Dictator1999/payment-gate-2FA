<?php

namespace App\Http\Controllers;

use App\order;
use App\Shipt;
use App\Traits\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;

class PaypalController extends Controller
{
    //payment trait
    use Payment;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payWithPaypal(Request $request)
    {
        $request['payment_gateway'] = "PAYPAL";
        $provider = new ExpressCheckout();
        $data = $this->data($request);
        $data['return_url'] = route('paypal.success');
        $data['cancel_url'] = url('/cart');
        if($data){
            $response = $provider->setExpressCheckout($data);
            return redirect($response['paypal_link']);
        }
    }

    public function payWithPaypalsiccess(Request $request)
    {
        $provider = new ExpressCheckout;
        $token = $request->token;
        $response = $provider->getExpressCheckoutDetails($token);
        $updateInfoSuccess = $this->updateInfoAfterPayment($response);
        if($updateInfoSuccess = true){
            return view('payment.payment')->with([
                'sucMsg'=>'<span class="alert alert-success d-block">You have successfully order your product.</span>',
            ]);
        }
    }
}
