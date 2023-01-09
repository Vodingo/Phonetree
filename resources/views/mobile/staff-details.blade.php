@extends('mobile.master')

@push('scripts')
    <script src="{{ asset('js/mobile/data-entry.js?v=1') }}"></script>
    <script src="{{ asset('js/mobile/dashboard.js?v=1') }}"></script>
@endpush
   
@section('content')
    <!-- Start of first page: #data-entry-page -->
    <div data-role="page" id="data-entry-page">

        <div data-role="header" class="pt-4">

            <h1>Staff Accountability Data Entry</h1>

            @include('mobile.partials.navbar')
            
        </div><!-- /header -->

        <div role="main" class="ui-content">

            <h3 class="text-center">Roll Call Data Entry</h3>

            <form class="ui-grid-a">
                <div class="ui-block-a">
                    <select id="sessions" name="sessions" data-native-menu="false" data-shadow="false" data-mini="true">
                        <option>Select Session</option>
                    </select>
                </div>
                <div class="ui-block-b">
                    <a href="#" class="ui-btn ui-mini ui-icon-plus ui-btn-icon-left">Add Session</a>
                </div>
            </form>

            <select id="supervisor" name="supervisor" data-native-menu="false" data-shadow="false" data-mini="true">
                <option>Select Supervisor</option>
            </select>

            <ul id="supervisor-staff-list" data-role="listview" data-autodividers="false" data-filter="true" data-inset="true">
                
            </ul>
            
        </div><!-- /content -->
    </div><!-- /page one -->

    <!-- Start of dashboard page: #dashboard-page -->
    <div data-role="page" id="dashboard-page" data-theme="a">

        <div data-role="header">
 
            <h1>CWS Staff Accountability</h1>

            <div data-role="navbar">
                <ul>
                    <li><a href="#data-entry-page" data-icon="home" data-transition="pop">Accountability Form</a></li>
                    <li><a href="#" data-icon="bullets" class="ui-btn-active">Dashboard</a></li>
                </ul>
            </div><!-- /navbar -->

        </div><!-- /header -->

        <div role="main" class="ui-content">
            <h2>Dashboard</h2>
           
            <div class="ui-grid-a">
                <select id="dashboard-sessions" name="dashboard-sessions" data-native-menu="false" data-shadow="false" data-mini="true">
                    <option>Select Session</option>
                </select>
            </div>

            <div class="ui-grid-a">
                <h3>Summary</h3>
                <canvas id="summery-chart"></canvas>
            </div>

            <div class="ui-grid-a">
                <ul data-role="listview" data-count-theme="b" data-inset="true">
                    <li><a href="#staff-list?accounted=true">Accounted Staff <span class="ui-li-count">200</span></a></li>
                    <li><a href="#staff-list?accounted=false">Unaccounted Staff <span class="ui-li-count">20</span></a></li>
                    <li><a href="#staff-list?accounted=undefined">Staff Not Contacted <span class="ui-li-count">225</span></a></li>
                </ul>
            </div>

        </div><!-- /content -->
    </div><!-- /page dashboard -->

    <!-- Start of entry popup page: #popup -->
    <div data-role="page" id="entry-popup">

        <div data-role="header" data-theme="b">
            <h1>Update Staff Details</h1>
        </div><!-- /header -->

        <div role="main" class="ui-content">           
            <form>
                <label for="entry-staff-name">Staff Name:</label>
                <input type="text" disabled="disabled" name="entry-staff-name" id="entry-staff-name" value="">
                <label for="entry-supervisor-name">Supervisor:</label>
                <input type="text" disabled="disabled" name="entry-supervisor-name" id="entry-supervisor-name" value="">
                <label for="entry-work-phone">Work Phone Number:</label>
                <input type="text" disabled="disabled" name="entry-work-phone" id="entry-work-phone" value="">
                <label for="entry-personal-phone">Personal Phone Number:</label>
                <input type="text" disabled="disabled" name="entry-personal-phone" id="entry-personal-phone" value="">
                <label for="entry-secondary-phone">Secondary Phone Number:</label>
                <input type="text" disabled="disabled" name="entry-secondary-phone" id="entry-secondary-phone" value="">
                <label for="entry-accounted">Accounted:</label>
                <input type="checkbox" data-role="flipswitch" name="entry-accounted" id="entry-accounted" data-on-text="Yes" data-off-text="No" data-wrapper-class="custom-size-flipswitch">
                <label for="entry-comments">Comment:</label>
                <textarea  cols="40" rows="8" name="entry-comments" id="entry-comments"></textarea>
            </form>

            <p><a href="#data-entry-page" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-icon-back ui-btn-icon-left">Back</a></p>
        </div><!-- /content -->

        <div data-role="footer">
            <h4>Page Footer</h4>
        </div><!-- /footer -->
    </div><!-- /page popup -->

    <!-- Start of staff list page: #staff-list -->
    <div data-role="page" id="staff-list" data-theme="a">

        <div data-role="header">
 
            <h1>Staff Accountability</h1>

        </div><!-- /header -->

        <div role="main" class="ui-content">
            <h2>Accounted Staff</h2>
           
            <div class="ui-grid-a">
                <ul id="staff-list" data-role="listview" data-autodividers="true" data-filter="true" data-inset="true">
                    <li><a href="#details-popup" data-rel="dialog" data-transition="pop">Adam Kinkaid</a></li>
                </ul>
            </div>

        </div><!-- /content -->
    </div><!-- /page four -->

    <!-- Start of accounted details page: #accounted-popup -->
    <div data-role="page" id="details-popup">

        <div data-role="header" data-theme="b">
            <h1>Staff Details</h1>
        </div><!-- /header -->

        <div role="main" class="ui-content">           
            <form>
                <label for="details-staff-name">Staff Name:</label>
                <input type="text" disabled="disabled" name="details-staff-name" id="details-staff-name" value="">
                <label for="details-supervisor-name">Supervisor:</label>
                <input type="text" disabled="disabled" name="details-supervisor-name" id="details-supervisor-name" value="">
                <label for="details-work-phone">Work Phone Number:</label>
                <input type="text" disabled="disabled" name="details-work-phone" id="details-work-phone" value="">
                <label for="details-personal-phone">Personal Phone Number:</label>
                <input type="text" disabled="disabled" name="details-personal-phone" id="details-personal-phone" value="">
                <label for="details-secondary-phone">Secondary Phone Number:</label>
                <input type="text" disabled="disabled" name="details-secondary-phone" id="details-secondary-phone" value="">
                <label for="details-accounted">Accounted:</label>
                <input type="checkbox" disabled="disabled" data-role="flipswitch" name="details-accounted" id="details-accounted" data-on-text="Yes" data-off-text="No" data-wrapper-class="custom-size-flipswitch">
                <label for="details-comments">Comment:</label>
                <textarea disabled="disabled" cols="40" rows="8" name="details-comments" id="details-comments"></textarea>
            </form>

            <p><a href="#staff-list" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-icon-back ui-btn-icon-left">Back</a></p>
        </div><!-- /content -->
    </div><!-- /page popup -->

@endsection
