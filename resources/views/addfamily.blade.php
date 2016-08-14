@extends('forms')


@section('form-panel-heading')
	Add New Family
@endsection

@section('formcontent')
	{!! Form::open(['url' => 'family']) !!}

		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name',null, ['class' => 'form-controller'] ) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Add Family' , ['class' => 'btn btn-primary form-control']) !!}
		</div>	

	{!! Form::close() !!}

@endsection

			



