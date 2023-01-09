@extends('mobile.master')

@section('content')
    <!-- Start of staff list page: #staff-list -->
    <div data-role="page" id="staff-list" data-theme="a">

        <div data-role="header" class="pt-4">
 
            <h1>CWS Staff Accountability</h1>
            
            @include('mobile.partials.navbar')

        </div><!-- /header -->

        <div role="main" class="ui-content">
           
            <h6 class="ui-bar ui-bar-a ui-corner-all text-center">
                {{ strtoupper($title) }} : <span class="text-muted">{{ $session->description }}</span>
            </h6>
           
            <div class="ui-body ui-body-a ui-corner-all">
                <div class="ui-grid-a">
                    <ol id="staff-list" data-role="listview" data-filter="true" data-inset="true" data-filter-placeholder="Filter staff by name">
                        @foreach ($staff as $row)
                            <li>
                                <h2>{{ $row['staff_name'] }}</h2>

                                <p>
                                    <span class="font-weight-bold">Phone Numbers: </span>
                                    <strong>

                                        @if (! empty($row['personal_phone']) )
                                            <a href="tel:{{ $row['personal_phone'] }}" class="text-primary">{{ $row['personal_phone'] }}</a>
                                        @endif

                                        @if (! empty($row['work_phone']) )
                                            / <a href="tel:{{ $row['work_phone'] }}" class="text-secondary">{{ $row['work_phone'] }}</a>
                                        @endif

                                        @if (! empty($row['secondary_phone']) )
                                            / <a href="tel:{{ $row['secondary_phone'] }}" class="text-danger">{{ $row['secondary_phone'] }}</a>
                                        @endif

                                    </strong>
                                </p>

                                <p>
                                    <span class="font-weight-bold">Supervisor:</span> <span class="text-success">{{ $row['supervisor'] }}</span>
                                </p>

                                @if ($contacted == 1) 
                                    <p><span class="font-weight-bold"> Comment: </span> <span class="text-dark">{{ $row['comments'] }}</span></p>

                                    <p style="margin-top: 15px;">Updated By <span class="text-info">{{ $row['updated_by'] }} </span > at <span class="text-info">{{ $row['date_updated'] }}</span></p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <a href="#" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-icon-back ui-btn-icon-left ui-btn-b">Back to Dashboard</a>

        </div><!-- /content -->
    </div><!-- /page four -->
@endsection