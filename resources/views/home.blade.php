@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">Roll Call</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content">
    <div class="row">
        <div class="col">
            <div class="card card-info card-outline">
                <div class="card-body">                    

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert" id="update-roll-call-alert" style="display: none"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sessions">Sessions</label>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col p-0">
                                        <div id="sessions"></div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <button class="btn btn-sm btn-flat btn-primary" id="create-session">Create Session</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supervisor">Supervisor</label>
                            <div id="supervisor"></div>
                        </div>
                    </div>
                </div>

                    <label for="">Staff List</label>
                    <div id="staff-list"></div>

                </div>

            </div>
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

@endsection

@push('scripts')
    <script src="{{ asset('js/roll_call.js?v=3') }}"></script>
@endpush