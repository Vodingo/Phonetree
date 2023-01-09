
@extends('mobile.master')

@push('scripts')
<script src="{{ asset('js/mobile/data-entry.js?v=3') }}"></script>
@endpush

@section('content')
<!-- Start of entry popup page: #popup -->
<div data-role="page" id="entry-popup">

    <div data-role="header" data-theme="b">
        <h1>Account for Staff</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" id="create-entry-alert"></div>
            </div>
        </div>

        <form>
            <input type="hidden" name="staff-id" id="staff-id" value="{{ $staff->id}}">
            <label for="entry-staff-name">Staff Name:</label>
            <input type="text" disabled="disabled" name="entry-staff-name" id="entry-staff-name" value="{{ $staff->full_name}}">
            <label for="entry-supervisor-name">Supervisor:</label>
            <input type="text" disabled="disabled" name="entry-supervisor-name" id="entry-supervisor-name" value="{{ $staff->supervisor_name}}">
            <label for="entry-work-phone">Work Phone Number:</label>
            <a href="tel:{{ $staff->work_phone}}">{{ $staff->work_phone}}</a>
            <label for="entry-personal-phone">Personal Phone Number:</label>
            <a href="tel:{{ $staff->personal_phone }}">{{ $staff->personal_phone }}</a>
            <label for="entry-secondary-phone">Secondary Phone Number:</label>
            <a href="tel:{{ $staff->secondary_phone }}">{{ $staff->secondary_phone  }}</a>
            <label>Staff Status:</label>
            <input type="radio" name="status" id="not-contacted" value="" {{ ($staff->accounted != 1 || $staff->accounted != 2) ? 'checked' : '' }}>
            <label for="not-contacted">Staff not contacted</label>
            <input type="radio" name="status" id="accounted" value="1" {{ ($staff->accounted == 1) ? 'checked' : '' }}>
            <label for="accounted">Staff accounted for</label>
            <input type="radio" name="status" id="unaccounted" value="2" {{ ($staff->accounted == 2) ? 'checked' : '' }}>
            <label for="unaccounted">Staff not accounted for</label>
            <label for="entry-comments">Comment:</label>
            <textarea  cols="40" rows="8" name="entry-comments" id="entry-comments">{{ $staff->comments }}</textarea>
        </form>

        <div class="ui-grid-a">
            <div class="ui-block-a">
                <a href="#" id="update-staff-status" class="ui-btn ui-shadow ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left">Save</a>
            </div>
            <div class="ui-block-b">
                <a href="#" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-icon-back ui-btn-icon-left">Back</a>
            </div>
        </div>

    </div><!-- /content -->
</div><!-- /page popup -->
@endsection
