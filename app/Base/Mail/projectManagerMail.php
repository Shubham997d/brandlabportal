<?php

namespace App\Base\Mail;

use App\Base\Models\Token;
use App\Base\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class projectManagerMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $value;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
        
        return $this->markdown('emails.project-manager-monthly-email')
                    ->subject('Reminder for Projects')
                    ->with(['value' => $this->value]);
    }
}
