@component('mail::message')    
Hi {{ $mailData['to_username'] }},

<br>
{!!$mailData['middle_content']!!}

@if(isset($mailData['button_url']))
@component('mail::button', ['url' => url($mailData['button_url'])])
{{$mailData['button_text'] ?? ''}}
@endcomponent
@endif

{{$mailData['bottom_content'] ?? ''}}

@endcomponent