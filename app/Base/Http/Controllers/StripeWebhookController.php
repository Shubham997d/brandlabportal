<?php

namespace App\Base\Http\Controllers;

use Exception;
use DateTimeZone;
use App\Base\Models\User;
use App\Base\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Base\Mail\stripeWebhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('accessWebhook');
    }
    public function checkoutSessionCompleted($session = null)
    {
        
        foreach (explode(',', env('MAIL_ADDRESSES')) as $email) {
            $data = ['customer_name' => $session->customer_details->name, 'customer_email' => $session->customer_details->email, 'amount' => $session->amount_total / 100 ,'currency' => strtoupper($session->currency) ];
            Mail::to($email)->send(new stripeWebhook($data));            
        } 
        
        return response()->json([
            'status'  => 'success',
            'message' => 'email sent successfully'
        ]);
        
    }


    public function accessWebhook(Request $request){
      
        $endpoint_secret = 'whsec_aJWCL9YYfw1gNDN1GfFFH4ce1HX1V0Bv';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        http_response_code(400);
        exit();
        }

        $session = $event->data->object;
       
        $this->checkoutSessionCompleted($session);

    }

    
    

    
}
