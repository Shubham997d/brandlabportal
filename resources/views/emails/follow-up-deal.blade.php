@component('mail::message')    
Hi {{ $value['owner_details']['username'] }},

<br>
    This is just a reminder that the following deals <b> {{$value['deal_titles']}} </b> has been stagnant for more than 14 days.
    @component('mail::button', ['url' => url('login')])
            Click here to login
    @endcomponent
@endcomponent