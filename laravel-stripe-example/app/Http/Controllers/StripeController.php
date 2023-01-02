<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use Session;

class StripeController extends Controller
{
 /**
     * payment view
     */
    public function handleGet()
    {
        return view('home');
    }

    /**
     * handling payment with POST
     */
    public function handlePost(Request $request)
    {

// ;
// $body = json_decode($request->getBody());
// dd($body);
//         // Stripe\Charge::create ([
        //         "amount" => 100 * 150,
        //         "currency" => "inr",
        //         "source" => $request->stripeToken,
        //         "description" => "Making test payment."
        // Stripe\Stripe::PaymentInten->create(

        //            [ "customer" => 1,
        //             "amount" => 100 * 150,
        //             "description" => 'Rails Stripe transaction',
        //             "currency" => 'usd',]
        //         );
        // ]);
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
          $stripe->paymentIntents->create([
            'amount' => 100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'payment_method_data' => [
                'type' => 'card',
                'card' => [
                    'token' => $request->stripeToken
                ]
            ]
        ]);
        Session::flash('success', 'Payment has been successfully processed.');

        return back();
    }
}
