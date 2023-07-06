@component('mail::message')
{{$authUserName}}

has invited you to join the {{$company}} website 

@component('mail::button', ['url' => url('register')])
Click here to register
@endcomponent

@endcomponent 