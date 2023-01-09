@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Departments') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" id="create-department">Create Department</button>
					<button class="btn btn-sm btn-flat btn-outline-info" type="button" id="edit-department">Edit Department</button>
					<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-department">Delete Department</button>
				</div>
			</div>
		</div>

		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="departments" ></div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="createDepartment" tabindex="-1" role="dialog" aria-labelledby="createDepartmentTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createDepartmentTitle">Create Department</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="alert alert-danger" role="alert" id="create-department-alert"></div>

			<div class="form-group row">
				<label for="name" class="col-form-label text-md-right">Name</label>
				<input id="name" type="text" name="name" required>
			</div>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-department">Create</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editDepartment" tabindex="-1" role="dialog" aria-labelledby="editDepartmentTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDepartmentTitle">Edit Department</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

			<input type="hidden" id="edit-id">

            <div class="alert alert-danger" role="alert" id="edit-department-alert"></div>

			<div class="form-group row">
				<label for="edit-name" class="col-form-label text-md-right">Name</label>
				<input id="edit-name" type="text" name="name" required>
			</div>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="update-department">Update</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/settings/departments.js') }}"></script>
@endpush