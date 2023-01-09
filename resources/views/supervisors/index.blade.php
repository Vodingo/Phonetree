@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Supervisors') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
 
            <div class="card card-info card-outline flex-fill fill d-flex flex-column">
				<div class="card-header">
                    <div class="row">
                        <div class="col text-right">
                            <button class="btn btn-sm btn-flat btn-outline-primary" type="button" id="create-supervisor">Add Supervisor</button>
                            <!--button class="btn btn-sm btn-flat btn-outline-success" type="button" id="add-supervisor-staff">Add Staff to Supervisor</button>
                            <button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-supervisor-staff">Remove Supervised Staff</button-->
                            <button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-supervisor">Delete Supervisor</button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-1 flex-fill fill d-flex flex-column">
                    <!--div id='supervisorsSplitter' class="flex-fill fill d-flex flex-column"-->
                        <div>
                            <div id="supervisors"></div>
                        </div>
                        <!--div>
                            <div id="supervisors-staff"></div>
                        </div>
                    </div-->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSupervisor" tabindex="-1" role="dialog" aria-labelledby="addSupervisorTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSupervisorTitle">Add Supervisor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">
            
            <div class="alert fade show d-none" role="alert" id="create-supervisor-alert">
                <p class="m-0" id="create-supervisor-response"></p>
            </div>

            <div class="form-group row">
        		<label for="supervisor" class="col-form-label text-md-right">Supervisor</label>
				<div id='supervisor'></div>
			</div>

			<div class="form-group row">
        		<label for="department" class="col-form-label text-md-right">Department</label>
				<div id='department'></div>
			</div>

			<div class="form-group row">
        		<label for="unit" class="col-form-label text-md-right">Unit</label>
				<div id='unit'></div>
			</div>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-supervisor">Add Supervisor</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--div class="modal fade" id="addSupervisorStaff" tabindex="-1" role="dialog" aria-labelledby="addSupervisorStaffTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSupervisorStaffTitle">Add Supervisor Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">
            <div class="alert fade show d-none" role="alert" id="create-supervisor-staff-alert">
                <p class="m-0" id="create-supervisor-staff-response"></p>
            </div>

            <div class="row mb-2">
              <div class="col">
                <label>Filter staff by name</label>
                <input type="text" id="filter-staff-names">
              </div>
            </div>

            <div id="staff-list"></div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-supervisor-staff">Add Supervisor</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div-->

@endsection

@push('scripts')
    <script src="{{ asset('js/settings/supervisors.js') }}"></script>
@endpush