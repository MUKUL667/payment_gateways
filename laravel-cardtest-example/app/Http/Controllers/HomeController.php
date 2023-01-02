<?php

namespace App\Http\Controllers;
use Stripe;
use Session;

use App\Models\User;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function show()
    {
        $users = User::select('email')->get();
        return view('table', compact('users'));
    }



    public static function DoesCustomerExistAtStripe ($email){

      Stripe\Stripe::setApiKey('sk_test_51LNDUbSHUxJ44dN3nDLryczoODIfdkK6YDzPvLvdUVuDhZ25EM8YHC3e1rXjQp6KqHM0psznQnkMiyGMA5QkeInr006RCK8vmP');

	// Search for the Laravel user (note that we passed this in the function) at Stripe
	// more on the customer object here: https://stripe.com/docs/api/customers/object
        $customer = \Stripe\Customer::all([
            'email' => $email,
            'limit' => 1,
        ]);

        if (isset($customer['data']) && count($customer['data'])) {

            // Get customer's payment method.  Read more about them here: https://stripe.com/docs/api/payment_methods
            $paymentMethod = \Stripe\PaymentMethod::all([
                'customer' => $customer['data'][0]->id,
                'type' => 'card'
            ]);

            if(isset($paymentMethod['data'][0]->id)){
                $customerExists = 2;
            }
            else{
                $customerExists = 1;
            }

        } else {
            $customerExists = 0;
        }

        return $customerExists;
    }

    public function publickey () {
        $pub_key = env('STRIPE_KEY');

        // Send publishable key details to client
        return response()->json(array('publicKey' => $pub_key));
      }





    public function CreateCustomerInStripe ($email) {

        Stripe\Stripe::setApiKey('sk_test_51LNDUbSHUxJ44dN3nDLryczoODIfdkK6YDzPvLvdUVuDhZ25EM8YHC3e1rXjQp6KqHM0psznQnkMiyGMA5QkeInr006RCK8vmP');

        // More here: https://stripe.com/docs/api/customers/create

        $customer = \Stripe\Customer::create(
            ['email' => $email]
        );

        // More here: https://stripe.com/docs/api/setup_intents/create

        $setupIntent = \Stripe\SetupIntent::create([
            'customer' => $customer->id
          ]);

          // Send Setup Intent details to client
          return response()->json($setupIntent);
    }


    public function ChargeTheCustomer ($email, $value2charge){

        // Set up payment data
        $data = [];

        Stripe\Stripe::setApiKey('sk_test_51LNDUbSHUxJ44dN3nDLryczoODIfdkK6YDzPvLvdUVuDhZ25EM8YHC3e1rXjQp6KqHM0psznQnkMiyGMA5QkeInr006RCK8vmP');

        // Get the customer, search by email
        $customer = \Stripe\Customer::all([
            'email' => $email,
            'limit' => 1,
        ]);

        // Get customer's payment method
        // $paymentMethod =  \Stripe\PaymentMethod::all([
        //     'customer' => $customer['data'][0]->id,
        //     'type' => 'card'
        // ]);
        $paymentMethod = \Stripe\PaymentMethod::all([
            'customer' => $customer['data'][0]->id,
            'type' => 'card'


        ]);

        //List of options can be found here: https://stripe.com/docs/api/payment_intents/create
        $data['payment_method'] = $paymentMethod['data'][0]->id;
        $data['amount'] = $value2charge;
        $data['currency'] = 'INR';
        $data['customer'] = $customer['data'][0]->id;
        $data['off_session'] = true;  //Read more here about why we need these paramenters
                                      //https://stripe.com/docs/payments/save-and-reuse#web-create-payment-intent-off-session
        $data['confirm'] = true;
        $stripe = new \Stripe\StripeClient(
            'sk_test_51LNDUbSHUxJ44dN3nDLryczoODIfdkK6YDzPvLvdUVuDhZ25EM8YHC3e1rXjQp6KqHM0psznQnkMiyGMA5QkeInr006RCK8vmP'
          );

        $paymentIntent = $stripe->paymentIntents->create($data);

        return response()->json($paymentIntent);

    }
}
