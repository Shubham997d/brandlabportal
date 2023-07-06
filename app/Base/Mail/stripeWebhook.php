<?php

namespace App\Base\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class stripeWebhook extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.s
     *
     * @return $this
     */
    public function build(Request $request)
    {
        
        return $this->markdown('emails.stripe-checkout-completed')
                    ->subject('Stripe Payment')
                    ->with(['data' => $this->data]);
    }
}
