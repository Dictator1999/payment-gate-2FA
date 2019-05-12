<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use App\User;

class StripeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payWithStripe()
    {

        return view('payment.paywithstripe');
    }

//    public function postPaymentWithStripe(Request $request){
//        Stripe::setApiKey("sk_test_zQDcOjvKnoViuz6f4zvmdXHj00bv0wRbVh");
//
//        $charge = \Stripe\Charge::create([
//            'amount' => 999,
//            'currency' => 'usd',
//            'source' => 'tok_visa',
//            'receipt_email' => 'jenny.rosen@example.com',
//        ]);
//    }


    public function postPaymentWithStripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            'amount' => 'required',
        ]);

        $input = $request->all();
        if ($validator->passes()) {
            $input = array_except($input,array('_token'));
            $stripe = Stripe::make('put here your stripe secret key');
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number'    => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year'  => $request->get('ccExpiryYear'),
                        'cvc'       => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    \Session::put('error','try again . stripe key error!!!');
                    return redirect()->route('addmoney.paywithstripe');
                }
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount'   => $request->get('amount'),
                    'description' => 'Add in wallet',
                ]);
                if($charge['status'] == 'succeeded') {
                    /**
                     * Write Here Your Database insert logic.
                     */
                    \Session::put('success','Money added successfully in wallet');
                    return redirect()->route('addmoney.paywithstripe');
                } else {
                    \Session::put('error','Money not add in wallet!!');
                    return redirect()->route('addmoney.paywithstripe');
                }
            } catch (Exception $e) {
                \Session::put('error',$e->getMessage());
                return redirect()->route('addmoney.paywithstripe');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                \Session::put('error',$e->getMessage());
                return redirect()->route('addmoney.paywithstripe');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                \Session::put('error',$e->getMessage());
                return redirect()->route('addmoney.paywithstripe');
            }
        }
        \Session::put('error','please fill out those field first!!');
        return redirect()->route('addmoney.paywithstripe');
    }
}