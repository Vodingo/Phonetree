
@extends('mobile.master')

@push('scripts')
    <script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/mobile/dashboard.js?v=4') }}"></script>
@endpush

@section('content')
    <!-- Start of dashboard page: #dashboard-page -->
    <div data-role="page" id="dashboard-page" data-theme="a">

        <div data-role="header" class="pt-4">

            <h1>CWS / RSC AFRICA PHONE TREE</h1>

            @include('mobile.partials.navbar')

        </div><!-- /header -->

        <div role="main" class="ui-content">

            <h5 class="ui-bar ui-bar-a ui-corner-all text-center">Management Dashboard</h5>

            <div class="ui-body ui-body-a ui-corner-all">
                <div class="ui-grid-a">
                    <select id="dashboard-sessions" name="dashboard-sessions" data-native-menu="false" data-shadow="false" data-mini="true">
                            @forelse($sessions as $session)
                                <option value="{{ $session['id'] }}">{{ $session['description'] }}</option>
                            @empty
                                <option>No active session exists.</option>
                            @endforelse
                    </select>
                </div>

                <div class="ui-grid-a">
                    <ul data-role="listview" data-count-theme="b" data-inset="true">
                        <li><a class="links" href="{{ url('/') }}/mobile/accounted?session=">Accounted Staff <span class="ui-li-count" id="accounted-count"></span></a></li>
                        <li><a class="links" href="{{ url('/') }}/mobile/unaccounted?session=">Unaccounted Staff <span class="ui-li-count" id="unaccounted-count"></span></a></li>
                        <li><a class="links" href="{{ url('/') }}/mobile/not-contacted?session=">Staff Not Contacted <span class="ui-li-count" id="not-contacted-count"></span></a></li>
                        <li>Total Staff</h3><span class="ui-li-count" id="total-staff-count"></span></li>
                    </ul>
                </div>

                <div class="ui-grid-a">
                    <canvas id="summary-chart"></canvas>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /page dashboard -->
@endsection
