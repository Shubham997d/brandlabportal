@component('layouts.app')

@slot('title') Request Status @endslot

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
<user-message :message="{{json_encode($message)}}" ></user-message>

@slot('script')
<script type="text/javascript">
    window.url = "{{ $token ?? '' }}";
    
</script>
<script src="{{ mix('/js/auth/registerationCompleted.min.js') }}"></script>
@endslot
@endcomponent
