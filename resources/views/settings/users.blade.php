@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('System Users') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
	<div class="card card-info card-outline flex-fill fill d-flex flex-column">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-sm btn-flat btn-outline-primary" type="button" data-toggle="modal" data-target="#addUserModal" data-backdrop="static">Add User</button>
					<button class="btn btn-sm btn-flat btn-outline-secondary" type="button" id="edit-user">Edit User</button>
					<button class="btn btn-sm btn-flat btn-outline-danger" type="button" id="deactivate-user">Deactivate User</button>
				</div>
			</div>
		</div>

		<div class="card-body p-1 flex-fill fill d-flex flex-column">
			<div id="users"></div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalTitle">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="row" style="height: 50px;">
                <div class="col-md-12">
                    <div class="alert" role="alert" id="add-user-alert"></div>
                </div>
            </div>
            
            <form id="add-user-form">
                <div class="form-group row">
                    <label for="name" class="col-form-label text-md-right">Name</label>
                    <div id="user"></div>
                </div>

                <div class="form-group row">
                    <label for="username" class="col-form-label text-md-right">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-form-label text-md-right">Email Address</label>
                    <input id="email" type="text" class="form-control" name="email" required>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-form-label text-md-right">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-form-label text-md-right">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group row">
                    <label for="role" class="col-form-label text-md-right">User Role</label>
                    <div id="role"></div>
                </div>
            </form>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="save-user">Save</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalTitle">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">

            <div class="row" style="height: 50px;">
                <div class="col-md-12">
                    <div class="alert" role="alert" id="edit-user-alert"></div>
                </div>
            </div>
            
            <form id="edit-user-form">
                <div class="form-group row">
                    <label for="name" class="col-form-label text-md-right">Name</label>
                    <input id="edit-name" type="text" class="form-control" name="name" required>
                    <input type="hidden" id="edit-id">
                </div>

                <div class="form-group row">
                    <label for="username" class="col-form-label text-md-right">Username</label>
                    <input id="edit-username" type="text" class="form-control" name="username" required>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-form-label text-md-right">Email Address</label>
                    <input id="edit-email" type="text" class="form-control" name="email" required>
                </div>

                <div class="form-group row">
                    <div id='update-password'>Update current users password</div>
                </div>
        
                <div class="form-group row">
                    <label for="password" class="col-form-label text-md-right">Password (Note: This will overide the users current password)</label>
                    <input id="edit-password" type="password" class="form-control" name="password" required>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-form-label text-md-right">Confirm Password</label>
                    <input id="edit-password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="form-group row">
                    <label for="edit-role" class="col-form-label text-md-right">User Role</label>
                    <div id="edit-role"></div>
                </div>

            </form>

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-flat btn-primary" id="update-user">Update</button>
        <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings/users.js?v=1') }}"></script>
@endpush