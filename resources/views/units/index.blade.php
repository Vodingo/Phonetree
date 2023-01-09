@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Units') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" id="create-unit">Create Unit</button>
					<button class="btn btn-sm btn-flat btn-outline-info" type="button" id="edit-unit">Edit Unit</button>
					<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-unit">Delete Unit</button>
				</div>
			</div>
		</div>
	
		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="units" ></div>
		</div>
	</div>
</div>
    

<!-- Modal -->
<div class="modal fade" id="createUnit" tabindex="-1" role="dialog" aria-labelledby="createUnitTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createUnitTitle">Create Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="alert alert-danger" role="alert" id="create-unit-alert"></div>

			<div class="form-group row">
				<label for="name" class="col-form-label text-md-right">Name</label>
				<input id="name" type="text" name="name">
			</div>

          </div>
      </div>
      <div class="modal-footer">
	  	<button type="button" class="btn btn-sm btn-primary" id="save-unit">Create</button>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editUnit" tabindex="-1" role="dialog" aria-labelledby="editUnitTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUnitTitle">Edit Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

			<input type="hidden" id="edit-id">
			
			<div class="alert alert-danger" role="alert" id="edit-unit-alert"></div>

			<div class="form-group row">
				<label for="name" class="col-form-label text-md-right">Name</label>
				<input id="edit-name" type="text" name="name">
			</div>

          </div>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-sm btn-primary" id="update-unit">Update</button>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings/units.js') }}"></script>
@endpush