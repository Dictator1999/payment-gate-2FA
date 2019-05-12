<?php
namespace App\Http\Controllers;
use App\Traits\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers;
session_start();

class PublicSslCommerzPaymentController extends Controller
{
    use Payment;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) 
    {
        $request['payment_gateway'] = 'SSL';
        $data = $this->data($request);
        $post_data = $this->orderInfo($data,$request);
        $sslc = new SSLCommerz();
        $payment_options = $sslc->initiate($post_data, false);
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function orderInfo($data,$request){
        if($data) {
            $post_data = array();
            //For SSL Payment Gateway start
            $post_data['currency'] = 'BDT';
            $post_data['total_amount'] = $data['total']; //M  # You cant not pay less than 10
            $post_data['tran_id'] = $data['invoice_id'];  //M // tran_id must be unique
            # CUSTOMER INFORMATION
            $post_data['cus_name'] = $data['customer'][0]['customer_name']; //M
            $post_data['cus_id'] = $data['customer'][0]['customer_id'];
            $post_data['cus_email'] = $data['customer'][0]['customer_email']; //M
            $post_data['cus_phone'] = $data['customer'][0]['customer_phone'];
            $post_data['cus_add1'] = $data['customer'][0]['customer_address'];
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = $data['customer'][0]['customer_city'];
            $post_data['cus_state'] = $data['customer'][0]['customer_state'];
            $post_data['cus_postcode'] = $data['customer'][0]['customer_zip'];
            $post_data['cus_country'] = $data['customer'][0]['customer_country'];
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = $data['shipt'][0]['ship_name'];
            $post_data['ship_add1 '] = $data['shipt'][0]['ship_address'];
            $post_data['ship_add2'] = "";
            $post_data['ship_city'] = $data['shipt'][0]['ship_city'];
            $post_data['ship_state'] = $data['shipt'][0]['ship_state'];
            $post_data['ship_postcode'] = $data['shipt'][0]['ship_zip'];
            $post_data['ship_country'] = $data['shipt'][0]['ship_country'];
            //For SSL Payment Gateway emd

            $server_name = $request->root() . "/";
            $post_data['success_url'] = $server_name . "success";
            $post_data['fail_url'] = $server_name . "fail";
            $post_data['cancel_url'] = $server_name . "cancel";
            return $post_data;
        }
    }

    public function success(Request $request) 
    {
        echo "Transaction is Successful";

        $sslc = new SSLCommerz();
        #Start to received these value from session. which was saved in index function.
        $tran_id = $_SESSION['payment_values']['tran_id'];
        #End to received these value from session. which was saved in index function.

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->select('order_id', 'order_status','currency','grand_total')->first();

        if($order_detials->order_status=='Pending')
        {
            $validation = $sslc->orderValidate($tran_id, $order_detials->grand_total, $order_detials->currency, $request->all());
            if($validation == TRUE) 
            {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */ 
                $update_product = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->update(['order_status' => 'Processing']);

                echo "<br >Transaction is successfully Complete";
            }
            else
            {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */ 
                $update_product = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->update(['order_status' => 'Failed']);
                echo "validation Fail";
            }    
        }
        else if($order_detials->order_status=='Processing' || $order_detials->order_status=='Complete')
        {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Complete";
        }
        else
        {
             #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }    
         


    }
    public function fail(Request $request) 
    {
         $tran_id = $_SESSION['payment_values']['tran_id'];
         $order_detials = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->select('order_id', 'order_status','currency','grand_total')->first();

        if($order_detials->order_status=='Pending')
        {
            $update_product = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->update(['order_status' => 'Failed']);
            echo "Transaction is Falied";                
        }
         else if($order_detials->order_status=='Processing' || $order_detials->order_status=='Complete')
        {
            echo "Transaction is already Successful";
        }  
        else
        {
            echo "Transaction is Invalid"; 
        }        
                            
    }

     public function cancel(Request $request) 
    {
        $tran_id = $_SESSION['payment_values']['tran_id'];

        $order_detials = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->select('order_id', 'order_status','currency','grand_total')->first();

        if($order_detials->order_status=='Pending')
        {
            $update_product = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->update(['order_status' => 'Canceled']);
            echo "Transaction is Cancel";                
        }
         else if($order_detials->order_status=='Processing' || $order_detials->order_status=='Complete')
        {
            echo "Transaction is already Successful";
        }  
        else
        {
            echo "Transaction is Invalid"; 
        }                 

        
    }
     public function ipn(Request $request) 
    {
        #Received all the payement information from the gateway  
      if($request->input('tran_id')) #Check transation id is posted or not.
      {

          $tran_id = $request->input('tran_id');

        #Check order status in order tabel against the transaction id or order id.
         $order_details = DB::table('orders')
                            ->where('order_id', $tran_id)
                            ->select('order_id', 'order_status','currency','grand_total')->first();

                if($order_details->order_status =='Pending')
                {
                    $sslc = new SSLCommerz();
                    $validation = $sslc->orderValidate($tran_id, $order_details->grand_total, $order_details->currency, $request->all());
                    if($validation == TRUE) 
                    {
                        /*
                        That means IPN worked. Here you need to update order status
                        in order table as Processing or Complete.
                        Here you can also sent sms or email for successfull transaction to customer
                        */ 
                        $update_product = DB::table('orders')
                                    ->where('order_id', $tran_id)
                                    ->update(['order_status' => 'Processing']);
                                    
                        echo "Transaction is successfully Complete";
                    }
                    else
                    {
                        /*
                        That means IPN worked, but Transation validation failed.
                        Here you need to update order status as Failed in order table.
                        */ 
                        $update_product = DB::table('orders')
                                    ->where('order_id', $tran_id)
                                    ->update(['order_status' => 'Failed']);
                                    
                        echo "validation Fail";
                    } 
                     
                }
                else if($order_details->order_status == 'Processing' || $order_details->order_status =='Complete')
                {
                    
                  #That means Order status already updated. No need to udate database.
                     
                    echo "Transaction is already successfully Complete";
                }
                else
                {
                   #That means something wrong happened. You can redirect customer to your product page.
                     
                    echo "Invalid Transaction";
                }  
        }
        else
        {
            echo "Inavalid Data";
        }      
    }

}
