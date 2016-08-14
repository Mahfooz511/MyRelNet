@extends('master')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Add New Person</div>
					@include ('errors.formerrors')
					{!! Form::open(['url' => 'person']) !!}
						
						<!-- <div class="form-group">
							{!! Form::select('family_id', $familyarray) !!}
						<div> -->

						@include ('personform',['submitButtonText' => 'Add Person'])

					{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>
@endsection

			



