@component('layouts.app')

@slot('title') Register @endslot

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

<register :roles="{{json_encode($roles)}}" :errors="{{json_encode($errors)}}" :user-inputs="{{json_encode($user_inputs)}}"></register>

@slot('script')
<script type="text/javascript">
    window.url = "{{ $token ?? '' }}";
    // window.errors = "{{ json_encode($errors) ?? '' }}";  
</script>
<script src="{{ mix('/js/auth/register.min.js') }}"></script>
@endslot
@endcomponent
