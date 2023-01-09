@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="row">
        <div class="col">
            <h3 class="m-0 text-dark">{{ __('Phone Tree Dashboard') }}</h3>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

<div class="content flex-fill fill d-flex flex-column">
    <div class="card card-info card-outline flex-fill fill d-flex flex-column">
        <div class="card-body flex-fill fill d-flex flex-column">
            <div class="row">
                <div class="col">
                <div class="form-group">
                    <label for="supervisor">Sessions</label>
                    <div id="sessions"></div>
                </div>
                </div>
            </div>

            <div class="flex-fill fill d-flex flex-column">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-chart-tab" data-toggle="tab" href="#nav-chart" role="tab" aria-controls="nav-chart" aria-selected="true">Summary</a>
                            <a class="nav-item nav-link" id="nav-supervisors-summary-tab" data-toggle="tab" href="#nav-supervisors-summary" role="tab" aria-controls="nav-supervisors-summary" aria-selected="true">Staff Accounted By Supervisors</a>
                            <a class="nav-item nav-link" id="nav-accounted-tab" data-toggle="tab" href="#nav-accounted" role="tab" aria-controls="nav-accounted" aria-selected="false">Accounted Staff</a>
                            <a class="nav-item nav-link" id="nav-unaccounted-tab" data-toggle="tab" href="#nav-unaccounted" role="tab" aria-controls="nav-unaccounted" aria-selected="false">Unaccounted Staff</a>
                            <a class="nav-item nav-link" id="nav-not-contacted-tab" data-toggle="tab" href="#nav-not-contacted" role="tab" aria-controls="nav-not-contacted" aria-selected="false">Staff not contacted</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane show active" id="nav-chart" role="tabpanel" aria-labelledby="nav-chart-tab">
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <canvas id="myChart"></canvas>
                                            <p id="updated-at" class="text-center text-muted mt-4"></p>
                                            <p class="text-center text-danger"> * Chart data updates after every minute</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-supervisors-summary" role="tabpanel" aria-labelledby="nav-supervisors-summary-tab">
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body p-1">
                                            <div class="row p-2">
                                                <div class="col text-right">
                                                    <button class="btn btn-sm btn-flat btn-info" id="supervisor-view-all" filter="true">View All Supervisors</button>
                                                    <button class="btn btn-sm btn-flat btn-success" id="supervisor-view-accounted-staff">View Accounted Staff</button>
                                                    <button class="btn btn-sm btn-flat btn-primary" id="supervisor-view-unaccounted-staff">View UnAccounted Staff</button>
                                                    <button class="btn btn-sm btn-flat btn-danger" id="supervisor-view-notcontacted-staff">View Staff Not Contacted</button>
                                                </div>
                                            </div>

                                            <div id="supervisors-summary"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-accounted" role="tabpanel" aria-labelledby="nav-accounted-tab">
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body p-1">
                                            <div class="row p-2">
                                                <div class="col-md-3">
                                                    <label>Search By Department</label>
                                                    <div id="filter-accounted-department"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Unit</label>
                                                    <div id="filter-accounted-unit"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Search By Supervisor</label>
                                                    <div id="filter-accounted-supervisor"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Staff Name</label>
                                                    <input type="text" id="filter-accounted-staff">
                                                </div>
                                                <div class="col-md-2 pt-4 mt-2">
                                                    <button class="btn btn-sm btn-flat btn-outline-info" id="clear-accounted-filters">Clear Search</button>
                                                </div>
                                            </div>
                                            <div id="accounted-staff"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-unaccounted" role="tabpanel" aria-labelledby="nav-unaccounted-tab">
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body p-1">
                                            <div class="row mb-2 align-items-center">
                                                <div class="col-md-3">
                                                    <label>Search By Department</label>
                                                    <div id="filter-unaccounted-department"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Unit</label>
                                                    <div id="filter-unaccounted-unit"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Search By Supervisor</label>
                                                    <div id="filter-unaccounted-supervisor"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Staff Name</label>
                                                    <input type="text" id="filter-unaccounted-staff">
                                                </div>
                                                <div class="col-md-2 pt-4 mt-2">
                                                    <button class="btn btn-sm btn-flat btn-outline-info" id="clear-unaccounted-filters">Clear Search</button>
                                                </div>
                                            </div>
                                            <div id="unaccounted-staff"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-not-contacted" role="tabpanel" aria-labelledby="nav-not-contacted-tab">
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card p-1">
                                            <div class="row p-2">
                                                <div class="col-md-3">
                                                    <label>Search By Department</label>
                                                    <div id="filter-not-contacted-department"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Unit</label>
                                                    <div id="filter-not-contacted-unit"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Search By Supervisor</label>
                                                    <div id="filter-not-contacted-supervisor"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Search By Staff Name</label>
                                                    <input type="text" id="filter-not-contacted-staff">
                                                </div>
                                                <div class="col-md-2 pt-4 mt-2">
                                                    <button class="btn btn-sm btn-flat btn-outline-info" id="clear-notcontacted-filters">Clear Search</button>
                                                </div>
                                            </div>
                                            <div id="staff-not-contacted"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="supervisorViewModal" tabindex="-1" role="dialog" aria-labelledby="supervisorViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="supervisorViewModalTitle"><span id="view-title"><span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <label>Session</label>
                    </div>
                    <div class="col-9">
                        <label id="view-session-name" class="text-secondary"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <label>Supervisor</label>
                    </div>
                    <div class="col-9">
                        <label id="view-supervisor-name" class="text-secondary"></label>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div id="supervisors-staff-view"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js?v=3') }}"></script>
@endpush