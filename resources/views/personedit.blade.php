@extends('master')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Person</div>
					@include ('errors.formerrors')
					{!! Form::model($person,['method' => 'PATCH', 'action' => ['PersonController@update', $person->id]]) !!}
						
						@include ('personform',['submitButtonText' => 'Update Person'])

					{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>
@endsection

			



