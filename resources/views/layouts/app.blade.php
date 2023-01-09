<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CWS/RSC Africa') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('jqwidgets/jqx-all.js') }}"></script>
    
    <script>
        var base_url = "{{url('/')}}";
        var theme = 'office';
    </script>

    @if(Auth::check())
        <script>
            var user = "{{ !empty(Auth::user()->details) ? Auth::user()->details->id : null }}";
        </script>
    @endif

    @stack('scripts')

    <!-- Styles -->
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Chart.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jqwidgets/styles/jqx.base.css') }}" rel="stylesheet">
    <link href="{{ asset('jqwidgets/styles/jqx.office.css') }}" rel="stylesheet">

</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper d-flex min-vh-100 flex-column">
        <nav class="main-header navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                          
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('management-dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="configsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   Settings <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="configsDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('sessions-settings') }}">
                                            {{ _('Roll Call Sessions') }}
                                        </a> 
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('staff') }}">
                                            {{ _('Staff List') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('supervisors') }}">
                                            {{ _('Supervisors') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('departments') }}">
                                            {{ _('Departments') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('units') }}">
                                            {{ _('Units') }}
                                        </a> 
                                    </li>

                                    <li class="dropdown-divider"></li>

                                    <li class="dropdown-submenu dropdown-hover">
                                        <a id="user-management-menu" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
                                            class="dropdown-item dropdown-toggle">{{ _('System Security') }}</a>
                                        <ul aria-labelledby="user-management-menu" class="dropdown-menu border-0 shadow">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('permissions') }}">
                                                    {{ _('Permissions') }}
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="{{ route('roles') }}">
                                                    {{ _('Roles') }}
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="{{ route('users-settings') }}">
                                                    {{ _('Users') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                   <!-- <a class="dropdown-item" href="{{ route('user-profile') }}">
                                        {{ _('Profile') }}
                                    </a>-->

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content-wrapper flex-fill fill d-flex flex-column px-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
