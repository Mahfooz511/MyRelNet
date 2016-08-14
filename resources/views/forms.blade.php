@extends('master-new')

@section('headerfiles')
	<script src="{{ asset('/js/bootstrap-filestyle.js') }}"></script>
@endsection

@section('content')
<div class="container forms" id="forms">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default form-panel">
				<div class="panel-heading form-panel-heading">@yield('form-panel-heading')</div>
				<div class="panelcontent">
					@include ('errors.formerrors')
				
					@yield('formcontent')
				</div>
				
			</div>
		</div>
	</div>
</div>
@yield('data')
@endsection





			



