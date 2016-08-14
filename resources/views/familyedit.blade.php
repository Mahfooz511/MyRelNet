@extends('forms')

@section('form-panel-heading')
	Edit Family
@endsection

@section('formcontent')

	{!! Form::model($family,['method' => 'PATCH', 'action' => ['FamilyController@update', $family->id]]) !!}

		<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name',null, ['class' => 'form-controller'] ) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Update Family' , ['class' => 'btn btn-primary form-control']) !!}
		</div>	

	{!! Form::close() !!}

@endsection
