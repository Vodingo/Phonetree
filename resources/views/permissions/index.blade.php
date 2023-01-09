@extends('layouts.app')

@section('content')
<div class="content-header">
	<div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('System Permissions') }}</h3>
        </div>
    </div>
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" data-toggle="modal" data-target="#addPermissionModal" data-backdrop="static">Add Permission</button>
					<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="edit-permission">Edit Permission</button>
					<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="delete-permission">Delete Permission</button>
				</div>
			</div>
		</div>

		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="permissions" ></div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog" aria-labelledby="addPermissionModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="addPermissionModalTitle">Add Permission</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      
	  		<div class="modal-body">
          		<div class="container">
            		<div class="row" style="height: 50px;">
                		<div class="col-md-12">
                    		<div class="alert" role="alert" id="add-permission-alert"></div>
                		</div>
            		</div>
            
					<div class="form-group row">
						<label for="name" class="col-form-label text-md-right">Permission Name</label>
						<input id="name" type="text" name="name">
					</div>
					<div class="form-group row">
						<label for="permission_description" class="col-form-label text-md-right">Permission Description</label>
						<input id="permission_description" type="text" name="permission_description">
					</div>
        		</div>
      		</div>

      		<div class="modal-footer">
        		<button type="button" class="btn btn-sm btn-flat btn-primary" id="save-permission">Save</button>
        		<button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalTitle" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="editPermissionModalTitle">Edit Permission</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      
	  		<div class="modal-body">
          		<div class="container">

            		<div class="row" style="height: 50px;">
                		<div class="col-md-12"><div class="alert" role="alert" id="edit-permission-alert"></div></div>
            		</div>
            
            		<input type="hidden" id="edit-id">
            
					<div class="form-group row">
						<label for="edit-name" class="col-form-label text-md-right">Permission Name</label>
						<input id="edit-name" type="text" name="edit-name">
					</div>
					<div class="form-group row">
						<label for="permission_description" class="col-form-label text-md-right">Permission Description</label>
						<input id="edit_permission_description" type="text" name="edit_permission_description">
					</div>
          		</div>
      		</div>

      		<div class="modal-footer">
        		<button type="button" class="btn btn-sm btn-flat btn-primary" id="update-permission">Update</button>
        		<button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings/permissions.js?v=1') }}"></script>
@endpush