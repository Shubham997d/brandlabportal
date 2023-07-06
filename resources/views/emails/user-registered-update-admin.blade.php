@component('mail::message')
Hi Admin,

A new user has signed up for Delivery Service. User details are given below:

@component('mail::panel')

@component('mail::table')
| Name          | Value                    |
| ------------- |:-------------:           |
| Full name     | {{$user->name}}          |
| Username      | {{$user->username}}      |
| Email         | {{$user->email}}         |
| Member type   | {{$user->role_name}}     |
| Password      | {{$user->password}}      |
| Job Title     | {{$user->job_title}}     |
| Yearly Salary | {{$user->yearly_salary}} |
| Avatar        | <div class="img-container" style="border-radius: 100px; width: 100px; height: 100px; overflow: hidden;"><img style="height: 100%; object-fit: cover;" src='{{ $user->avatar_path }}'></div> |

@endcomponent

<p style="font-weight:700;">Please click on accept to update user status to active or deny to delete user from database</p>
<div class="mail-button-wrap" style="text-align: center; margin-top: 10px; padding-top: 10px;"> 
    <a style="display: inline-block; font-weight: 500; padding: 8px 15px; background-color: #00ff00; border-radius: 5px; text-decoration: none; color: #fff;" href="{{$user->accept}}">Accept</a>
    <a style="display: inline-block; font-weight: 500; padding: 8px 15px; background-color: #ff0000; border-radius: 5px; text-decoration: none; color: #fff;" href="{{$user->deny}}">Deny</a>
</div>

@endcomponent

@endcomponent
