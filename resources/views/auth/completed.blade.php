@component('layouts.app')

@slot('title') Registration Completed @endslot

@section('style')
<style type="text/css">
    html, body {
        height: 100%;
    }
    body {
        background-color: #08b7ce;
    }
</style>
@endsection

<registeration-completed :message="{{json_encode($message)}}" ></registeration-completed>

@slot('script')
<script type="text/javascript">
    window.url = "{{ $token ?? '' }}";
    // window.errors = "{{ json_encode($errors) ?? '' }}";  
</script>
<script src="{{ mix('/js/auth/registerationCompleted.min.js') }}"></script>
@endslot
@endcomponent
