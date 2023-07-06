@component('mail::message')    
Hi Scott

<br>
    A Customer {{$data['customer_name']}} with email address {{$data['customer_email']}} has paid {{$data['amount']}} {{$data['currency']}} 
    
@endcomponent