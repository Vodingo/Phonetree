@extends('mobile.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/select-menu-filter.css') }}" />
@endpush 

@push('scripts')
<script src="{{ asset('js/mobile/data-entry.js?v=11') }}"></script>
<script src="{{ asset('js/mobile/select-menu-filter.js') }}"></script>
@endpush

@section('content')
<!-- Start of first page: #data-entry-page -->
<div data-role="page" id="data-entry-page">

    <div data-role="header" class="pt-4">

        <h1>CWS / RSC AFRICA PHONE TREE</h1>

        @include('mobile.partials.navbar')

    </div><!-- /header -->
    <div role="main">
        <div data-role="header" data-theme="b">
            <h1>HOME</h1>
        </div><!-- /header -->
        <p class="lead">Instructions for Supervisors:</p>
        <ul>
            <li>Select a session</li>
            <li>Select a supervisor</li>
            <li>Press or hold the staff name to open the Accounting page</li>
        </ul>
       
        <!-- sessions drop down list -->
        <select id="sessions" class="form-control">
            @forelse($sessions as $session)
            <option value="{{ $session['id'] }}">{{ $session['description'] }}</option>
            @empty
            <option>No active session exists.</option>
            @endforelse

        </select>

        <!-- supervisors drop down list -->
       
        <select id="supervisor" class="form-control">
            <option value="0">Select Supervisor</option>

            @forelse($supervisors as $supervisor)
            <option value="{{ $supervisor['id'] }}">{{ $supervisor['name'] }}</option>
            @empty
            <option>No supervisor found.</option>
            @endforelse
        </select>
        
        <!-- list of staff based on selection of session & supervisor -->
        <div class="card w-100">
            <!-- <h5 class="card-header text-white bg-primary text-center">Staff List</h5> -->
            <div class="card-header text-white bg-dark text-center">Staff List</div>
            <ul class="list-group list-group-flush" id="supervisor-staff-list">

            </ul>
        </div>

    </div>

</div><!-- /page one -->

<div data-role="page" data-dialog="true" id="create-session-page">
    <div data-role="header">
        <h2>Create Roll Call Session</h2>
    </div>
    <div class="ui-content" role="main">

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" id="create-session-alert"></div>
            </div>
        </div>

        <label for="session-date">Date:</label>
        <input type="date" data-clear-btn="false" name="session-date" id="session-date" value="">

        <label for="session-description">Description:</label>
        <input type="text" name="session-description" id="session-description" value="">

        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a href="#" id="create-session-btn" class="ui-btn ui-shadow ui-corner-all">Save</a>
            </div>
            <div class="ui-block-b">
                <a href="#data-entry-page" id="cancel-button" class="ui-btn ui-btn-b ui-shadow ui-corner-all" data-direction="reverse">Cancel</a>
            </div>
        </div>
    </div>
</div>

@endsection
