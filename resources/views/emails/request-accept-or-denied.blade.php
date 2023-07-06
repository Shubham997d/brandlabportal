@component('mail::message')
Hi {{$mailData['username']}},


{!!$mailData['middle_content']!!}


{{$mailData['bottom_content'] ?? null}}

@endcomponent