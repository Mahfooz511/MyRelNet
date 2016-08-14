@extends('forms')

@section('form-panel-heading')
	Copy Family
@endsection

@section('formcontent')
	{!! Form::open(['url' => 'family/copy', 'method' => 'POST']) !!}
		<div class="form-group">
			{!! Form::select('family_id', $families,'default', array('id' => 'family_id')) !!} 
		</div>

		<div class="form-group">
			{!! Form::submit('Copy' , ['class' => 'btn btn-primary form-control']) !!}
		</div>	

	{!! Form::close() !!}
@endsection

			



