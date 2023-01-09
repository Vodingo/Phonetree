<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Staff Accountability</title>
	
    <link rel="stylesheet" href="{{ asset('css/jquery.mobile-1.4.5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    @stack('styles')

    <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mobile-1.4.5.min.js') }}"></script>

    <script>
        var base_url = "{{url('/')}}";

        var supervisor = localStorage.getItem('supervisor');
        
        if (supervisor == null) {
            localStorage.setItem('supervisor', "{{ !empty(Auth::user()->details) ? Auth::user()->details->id : 0 }}")
        }

    </script>

    @stack('scripts')
    
</head>

<body>

    @yield('content')

</body>
</html>