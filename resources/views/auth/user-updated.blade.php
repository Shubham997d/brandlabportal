@component('layouts.app')

@slot('title') User Updated @endslot

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

<user-updated :message="{{json_encode($message)}}" :info-class="{{json_encode($class)}}" ></user-updated>

@slot('script')
<script type="text/javascript">
    window.url = "{{ $token ?? '' }}";
    // window.errors = "{{ json_encode($errors) ?? '' }}";  
</script>
<script src="{{ mix('/js/auth/registerationCompleted.min.js') }}"></script>
@endslot
@endcomponent
