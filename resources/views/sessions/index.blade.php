@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Roll Call Sessions') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
          <button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="filter-sessions">Filter Sessions</button>
					<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="clear-filters">Clear Filters</button>
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" id="create-session">Create New Session</button>
					<button class="btn btn-sm btn-flat btn-outline-info" type="button" id="edit-session">Edit Session</button>
          <button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-session">Delete Session</button>
					<button class="btn btn-sm btn-flat btn-outline-success" type="button" id="complete-session">Mark Session as Completed</button>
				</div>
			</div>
		</div>

		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="sessions" ></div>
		</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="createSession" tabindex="-1" role="dialog" aria-labelledby="createSessionTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createSessionTitle">Create Roll Call Session</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" id="create-session-alert"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="roll-call-date">Date</label>
            <div id="roll-call-date"></div>
        </div>

        <div class="form-group">
            <label for="roll-call-description">Description</label>
            <input type="text" class="form-control" id="roll-call-description">
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-flat btn-primary" id="save-session">Create</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editSession" tabindex="-1" role="dialog" aria-labelledby="editSessionTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSessionTitle">Update Roll Call Session</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" id="edit-session-alert"></div>
            </div>
        </div>

		<input type="hidden" id="edit-roll-call-id">

        <div class="form-group">
            <label for="edit-roll-call-date">Date</label>
            <div id="edit-roll-call-date"></div>
        </div>

        <div class="form-group">
            <label for="edit-roll-call-description">Description</label>
            <input type="text" class="form-control" id="edit-roll-call-description">
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-flat btn-primary" id="update-session">Update</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="filterSessions" tabindex="-1" role="dialog" aria-labelledby="filterSessionsTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterSessionsTitle">Filter Sessions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">
			
			<div class="form-group row">
				<label for="filter-description" class="col-form-label text-md-right">Description</label>
				<input id="filter-description" type="text" class="form-control">
			</div>

			<div class="form-group row">
				<div id='filter-dates'>Filter by dates</div>
			</div>

			<div class="form-group row">
				<label for="filter-start-date" class="col-form-label text-md-right">Start Date</label>
				<input id="filter-start-date" type="text" class="form-control">
			</div>

			<div class="form-group row">
				<label for="filter-end-date" class="col-form-label text-md-right">End Date</label>
				<input id="filter-end-date" type="text" class="form-control">
			</div>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="filter">Filter</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/settings/sessions.js') }}"></script>
@endpush