@component('layouts.app')
    @slot('title') Reset password @endslot

@section('style')
<style type="text/css">
    html, body {
        height: 100%;
    }
    body {
        /* background-color: #08b7ce; */
    }
</style>
@endsection
    <div class="main-section md:bg-white container md:mx-4 md:mx-auto md:shadow-lg rounded md:mt-32 flex md:flex-row flex-col md:max-w-2xl lg:max-w-4xl justify-between auth-page forgot-password-form">
        <div class="md:w-1/2 text-center md:flex flex-col items-center px-8 py-4">
            <p class="text-teal-500 font-bold text-4xl">{{ config('app.name', 'BrandLab360') }}</p>
            {{-- <p class="text-teal-500 text-xl">@lang('auth.Sensible Approach to Work & Collaboration for Software Teams')</p> --}}
            <img src="/image/email.svg" alt="work desk" class="pt-4 hidden md:block w-64">
        </div>
        <div class=""></div>
        <div class="w-full md:bg-indigo-100 md:w-1/2 p-8">
            <p class="text-sm text-gray-500 pb-2">@lang('auth.Forgot your account’s password or having trouble logging into your account?')</p>
            <p class="text-sm text-gray-500 pb-8">@lang('auth.Enter your email address and we’ll send you a recovery link.')</p>
            <h3 class="text-gray-500 font-medium">Email</h3>
            @if (session('status'))
                <div class="bg-teal-100 text-teal-800 border-teal-500 border-l-2 p-2 mt-2">
                    {{ session('status') }}
                </div>
            @endif
            <form role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}

                <p class="py-4">
                    <input id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" type="email" placeholder="john.doe@example.com" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-red-light block pt-5" style="font-size: 14px;">{{ $errors->first('email') }}</span>
                    @endif
                </p>
                <p class="py-4">
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white text-xl font-medium py-1 px-4 rounded auth-btn">Send Reset Link</button>
                </p>
                <a href="login" class="no-underline text-indigo-500 text-sm auth-link">Go to Login</a>
            </form>
        </div>
    </div>

    @slot('script') 
    <script src="{{ mix('/js/home.min.js') }}"></script>
    
    @endslot
@endcomponent
