@extends('forms')

@section('form-panel-heading')
	Rename Family
@endsection

@section('formcontent')
	{!! Form::open(['url' => 'family/rename', 'method' => 'POST']) !!}
		<div class="form-group">
			{!! Form::select('family_id', $families, 'default', array('id' => 'family_id')) !!} 
		</div>

		<div class="form-group">
			{!! Form::label('name', 'Rename To:') !!}
			{!! Form::text('name',null, ['class' => 'form-control'] ) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Rename ' , ['class' => 'btn btn-primary form-control']) !!}
		</div>	

	{!! Form::close() !!}
@endsection

			



