@extends('layouts.app')

@section('content')
<div class="col">
	<div class="row d-flex h-100 flex-column">
		<div class="col-md-12 text-center pt-5">

			<img src="{{ asset('images/logo-accessdenied.png') }}">

			<p class="mt-5">You do not have permission to view this page.</p>

			<p> Please contact the <a href="mailto:IT@cwsafrica.org">IT Department (IT@CWSAfrica.org)</a> for more information.</p>

			<a href="{{ url('/') }}" class="btn btn-flat btn-sm btn-primary mt-5">Go to Homepage</a>
		
		</div>
    </div>
</div>
@endsection
