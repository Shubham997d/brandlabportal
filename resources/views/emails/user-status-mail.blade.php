@component('mail::message')
Hi {{$value->username}},
<br>
Is all your information and salary up to date?

If not click <a href="{{url('profile/' . $value->username)}}">here.</a>
@endcomponent