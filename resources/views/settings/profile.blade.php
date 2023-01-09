@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('User Profile') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content">
    <div class="row">
        <div class="col">
            <div class="card card-info card-outline">

                <div class="card-body p-1">

                    <form id="user-profile-form" method="POST" action="{{ route('update-profile') }}">

                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Personal Info</h5>
                            </div>
                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        {{ session('status') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="first-name" class="col-form-label text-md-right">First Name</label>
                                        <input id="first-name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ ( ! empty($profile) ? $profile->first_name : '') }}">
                                        <input id="id" name="id" type="hidden" value="{{ ( ! empty($profile) ? $profile->id : '') }}">

                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                
                                    <div class="form-group col-md-4">
                                        <label for="last-name" class="col-form-label text-md-right">Last Name</label>
                                        <input id="last-name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ ( ! empty($profile) ? $profile->last_name : '') }}">

                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group col-md-4">
                                        <label for="other-name" class="col-form-label text-md-right">Other Name(s)</label>
                                        <input id="other-name" type="text" class="form-control @error('other_name') is-invalid @enderror" name="other_name" value="{{ ( ! empty($profile) ? $profile->other_name : '') }}">
                                        @error('other_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Contacts</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="work-phone" class="col-form-label text-md-right">Work Phone</label>
                                        <input id="work-phone" type="text" class="form-control @error('work_phone') is-invalid @enderror" name="work_phone" value="{{ ( ! empty($profile) ? $profile->work_phone : '') }}">
                                        
                                        @error('work_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                
                                    <div class="form-group col-md-4">
                                        <label for="personal-phone" class="col-form-label text-md-right">Personal Phone</label>
                                        <input id="personal-phone" type="text" class="form-control @error('personal_phone') is-invalid @enderror" name="personal_phone" value="{{ ( ! empty($profile) ? $profile->personal_phone : '') }}">
                                        
                                        @error('personal_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="secondary-phone" class="col-form-label text-md-right">Secondary Contact</label>
                                        <input id="secondary-phone" type="text" class="form-control @error('secondary_phone') is-invalid @enderror" name="secondary_phone" value="{{ ( ! empty($profile) ? $profile->secondary_phone : '') }}">
                                        
                                        @error('secondary_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <div id='update-password'>Update Password</div>
                                <input type="hidden" name="updatepassword" id="update-password-value" value="false">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="new-password" class="col-form-label text-md-right">New Password</label>
                                        <input id="new-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" disabled="true">
                                        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="confirm-new-password" class="col-form-label text-md-right">Confirm New Password</label>
                                        <input id="confirm-new-password" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" disabled="true">
                                        
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-sm btn-flat btn-primary float-right" id="update-profile">Update Profile</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
   
@endsection

@push('scripts')
    <script src="{{ asset('js/settings/profile.js') }}"></script>
@endpush