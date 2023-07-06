@component('mail::message')
Hi {{$value['username']}},
<br>
Here is a reminder to check in with the team from (<b>{{$value['name']}}</b>) {{$value['count']}} to offer them any support and training of the software.

@endcomponent