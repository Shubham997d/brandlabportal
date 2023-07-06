@component('mail::message')

Hi {{ $user->name }},

Your account status has been updated to active by admin. Please check user details given below:

@component('mail::panel')
@component('mail::table')
| Name          | Value                    |
| ------------- |:-------------:           |
| Full name     | {{$user->name}}          |
| Username      | {{$user->username}}      |
| Email         | {{$user->email}}         |
| Member type   | {{$user->role_name}}     |
| Job Title     | {{$user->designation}}   |
| Yearly Salary | {{$user->yearly_salary}}        |
| Avatar        | <div class="img-container" style="border-radius: 100px; width: 100px; height: 100px; overflow: hidden;"><img style="height: 100%; object-fit: cover;" src='{{ $user->avatar_path }}'></div> |

@endcomponent
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
