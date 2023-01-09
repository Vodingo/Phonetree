@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Staff List') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="filter-staff">Filter</button>
					<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="clear-filters">Clear Filters</button>
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" id="create-staff">Create Staff</button>
					<button class="btn btn-sm btn-flat btn-outline-info" type="button" id="edit-staff">Edit Staff</button>
					<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-staff">Delete Staff</button>
					<button class="btn btn-sm btn-flat btn-outline-success" type="button" id="upload-staff-modal">Upload Staff List</button>
				</div>
			</div>
		</div>

		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="staff"></div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="uploadStaffList" tabindex="-1" role="dialog" aria-labelledby="uploadStaffListTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadStaffListTitle">Upload Staff List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="alert alert-danger d-none" role="alert" id="upload-file-alert"></div>

			<div class="form-group row">
				<label for="name" class="col-form-label text-md-right">Staff List Excel File</label>
				<div class="custom-file">				
					<input type="file" id="staff-list-file" name="staff-list-file">
					<!--label class="form-control-label" for="customFile">Choose file</label-->					
				</div>
			</div>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="upload-staff-list">Upload</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="filterStaff" tabindex="-1" role="dialog" aria-labelledby="filterStaffTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterStaffTitle">Filter Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">

			<div class="form-group row">
				<label for="filter-description" class="col-form-label text-md-right">Name</label>
				<input id="filter-name" type="text">
			</div>

			<div class="form-group row">
        		<label for="filter-description" class="col-form-label text-md-right">Department</label>
				<div id='filter-department'></div>
			</div>

			<div class="form-group row">
        		<label for="filter-description" class="col-form-label text-md-right">Unit</label>
				<div id='filter-unit'></div>
			</div>

			<div class="form-group row">
        		<label for="filter-description" class="col-form-label text-md-right">Supervisor</label>
				<div id='filter-supervisor'></div>
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

<div class="modal fade" id="createStaff" tabindex="-1" role="dialog" aria-labelledby="createStaffTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createStaffTitle">Create Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

	  	<div class="alert fade show d-none" role="alert" id="create-staff-alert">
		  	<p class="m-0" id="create-staff-response"></p>
		</div>

		<div class="container">

			<div class="row">
				<div class="form-group col-md-4">
					<label for="first-name" class="col-form-label text-md-right">First Name</label>
					<input id="first-name" type="text">
				</div>

				<div class="form-group col-md-4">
					<label for="last-name" class="col-form-label text-md-right">Last Name</label>
					<input id="last-name" type="text">
				</div>

				<div class="form-group col-md-4">
					<label for="other-name" class="col-form-label text-md-right">Other Name(s)</label>
					<input id="other-name" type="text">
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-4">
					<label for="work-phone" class="col-form-label text-md-right">Work Phone</label>
					<input id="work-phone" type="text">
				</div>

				<div class="form-group col-md-4">
					<label for="personal-phone" class="col-form-label text-md-right">Personal Phone</label>
					<input id="personal-phone" type="text">
				</div>

				<div class="form-group col-md-4">
					<label for="secondary-phone" class="col-form-label text-md-right">Secondary Contact Phone</label>
					<input id="secondary-phone" type="text">
				</div>
			</div>
			
			<div class="row">
				<div class="form-group col-md-6">
					<label for="email" class="col-form-label text-md-right">Email Address</label>
					<input id="email" type="text">
				</div>

				<div class="form-group col-md-6">
					<label for="employee_no" class="col-form-label text-md-right">Employee Number</label>
					<input id="employee_no" type="text">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="department" class="col-form-label text-md-right">Department</label>
					<div id='department'></div>
				</div>

				<div class="form-group col-md-6">
					<label for="unit" class="col-form-label text-md-right">Unit</label>
					<div id='unit'></div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-6">
					<label for="supervisor" class="col-form-label text-md-right">Supervisor</label>
					<div id='supervisor'></div>
				</div>

				<div class="form-group col-md-6">
					<label for="site" class="col-form-label text-md-right">Site</label>
					<div id='site'></div>
				</div>
			</div>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-staff">Create Staff</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editStaff" tabindex="-1" role="dialog" aria-labelledby="editStaffTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStaffTitle">Edit Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

	  	<div class="alert alert-dismissible fade show d-none" role="alert" id="edit-staff-alert">
			<p class="m-0" id="edit-staff-response"></p>
		</div>

		<div class="container">

			<input type="hidden" id="edit-id">

			<div class="row">
				<div class="form-group col-md-4">
					<label for="edit-first-name" class="col-form-label text-md-right">First Name</label>
					<input id="edit-first-name" type="text" readonly=true style="background-color:aliceblue">
				</div>

				<div class="form-group col-md-4">
					<label for="edit-last-name" class="col-form-label text-md-right">Last Name</label>
					<input id="edit-last-name" type="text" readonly=true style="background-color:aliceblue">
				</div>

				<div class="form-group col-md-4">
					<label for="edit-other-name" class="col-form-label text-md-right">Other Name(s)</label>
					<input id="edit-other-name" type="text" readonly=true style="background-color:aliceblue">
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-4">
					<label for="edit-work-phone" class="col-form-label text-md-right">Work Phone</label>
					<input id="edit-work-phone" type="text" readonly=true style="background-color:aliceblue">
				</div>

				<div class="form-group col-md-4">
					<label for="edit-personal-phone" class="col-form-label text-md-right">Personal Phone</label>
					<input id="edit-personal-phone" type="text" readonly=true style="background-color:aliceblue">
				</div>

				<div class="form-group col-md-4">
					<label for="edit-secondary-phone" class="col-form-label text-md-right">Secondary Contact Phone</label>
					<input id="edit-secondary-phone" type="text" readonly=true style="background-color:aliceblue">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="email" class="col-form-label text-md-right">Email Address</label>
					<input id="edit_email" type="text" readonly=true style="background-color:aliceblue">
				</div>

				<div class="form-group col-md-6">
					<label for="employee_no" class="col-form-label text-md-right">Employee Number</label>
					<input id="edit_employee_no" type="text" readonly=true style="background-color:aliceblue">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="edit-department" class="col-form-label text-md-right">Department</label>
					<div id='edit-department'></div>
				</div>

				<div class="form-group col-md-6">
					<label for="edit-unit" class="col-form-label text-md-right">Unit</label>
					<div id='edit-unit'></div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-6">
					<label for="edit-supervisor" class="col-form-label text-md-right">Supervisor</label>
					<div id='edit-supervisor'></div>
				</div>

				<div class="form-group col-md-6">
					<label for="edit-site" class="col-form-label text-md-right">Site</label>
					<div id='edit-site'></div>
				</div>
			</div>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="update-staff">Update Staff</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/settings/staff.js?v=2') }}"></script>
@endpush