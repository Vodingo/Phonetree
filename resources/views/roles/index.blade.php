@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('System Roles') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
   
            <div class="card card-info card-outline flex-fill fill d-flex flex-column">
				<div class="card-header">
                    <div class="row">
                        <div class="col text-right">
							<button class="btn btn-sm btn-flat btn-outline-primary" type="button" data-toggle="modal" data-target="#addRoleModal" data-backdrop="static">Add Role</button>
							<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="edit-role">Edit Role</button>
							<button class="btn btn-sm btn-flat btn-outline-success" type="button" id="assign-permissions">Assign Permissions</button>
							<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-role">Delete Role</button>
						</div>
					</div>
				</div>

				<div class="card-body p-1 flex-fill fill d-flex flex-column">
					<div id='rolesSplitter' class="flex-fill fill d-flex flex-column">
						<div>
							<div id="roles"></div>
						</div>
						<div>
							<div id="role-permissions"></div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleModalTitle">Add Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="row" style="height: 50px;">
                <div class="col-md-12">
                    <div class="alert" role="alert" id="add-role-alert"></div>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="name" class="col-form-label text-md-right">Role Name</label>
                <input id="name" type="text" name="name">
            </div>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-role">Save</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoleModalTitle">Edit Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="row" style="height: 50px;">
                <div class="col-md-12">
                    <div class="alert" role="alert" id="edit-role-alert"></div>
                </div>
            </div>
            
            <input type="hidden" id="edit-id">
            
            <div class="form-group row">
                <label for="edit-name" class="col-form-label text-md-right">Role Name</label>
                <input id="edit-name" type="text" name="edit-name">
            </div>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="update-role">Update</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="assignPermissionsModal" tabindex="-1" role="dialog" aria-labelledby="assignPermissionsModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignPermissionsModalTitle">Manage Role Permissions: <span class="text-info" id="permissions-title"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<div class="container">
				<div class="row" style="height: 50px;">
					<div class="col-md-12">
						<div class="alert" role="alert" id="assign-permission-alert"></div>
					</div>
				</div>
      
        <div class="row mb-2">
          <div class="col">
            <label>Filter permission by name</label>
            <input type="text" id="filter-assign-permissions">
          </div>
        </div>

				<div id="assign-permissions-grid"></div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="update-permissions">Update Permission</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/settings/roles.js?v=2') }}"></script>
@endpush