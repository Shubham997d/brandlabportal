@component('mail::message')
Hi Admin,


{!!$mailData['middle_content']!!}
<br>
@component('mail::panel')

<p style="font-weight:700;">Please accept or deny the request</p>
<div class="mail-button-wrap" style="text-align: center; margin-top: 10px; padding-top: 10px;"> 
<a style="display: inline-block; font-weight: 500; padding: 8px 15px; background-color: #00ff00; border-radius: 5px; text-decoration: none; color: #fff;" href="{{$mailData['acceptUrl']}}">Accept</a>
<a style="display: inline-block; font-weight: 500; padding: 8px 15px; background-color: #ff0000; border-radius: 5px; text-decoration: none; color: #fff;" href="{{$mailData['denyUrl']}}">Deny</a>
</div>

@endcomponent

@endcomponent