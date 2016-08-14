@extends('forms')

@section('form-panel-heading')
	Split Family
@endsection

@section('formcontent')
	{!! Form::open(['url' => 'family/split', 'method' => 'POST']) !!}
			
		{!! Form::label('splitfamily', 'Split Family', array('class' => 'splitlabel')) !!}
		<div class="form-group">
			{!! Form::select('splitfamily', array(0 => 'Choose Family...' ) + $families,'default', array('id' => 'splitfamily')) !!} 
		</div>
	
		{!! Form::label('firstmember', 'By breaking relations between:(choose different members)', array('class' => 'splitlabel')) !!}
		<div class="form-group" >
			{!! Form::select('firstmember', array("" => 'Choose Member...'), 'default', array('id' => 'firstmember', 'class' => 'form-controller ')) !!} 
		</div>
		{!! Form::label('secondmember', 'And', array('class' => 'splitlabel')) !!}
		<div class="form-group" >
			
			{!! Form::select('secondmember', array("" => 'Choose Member...'),'default',array('id' => 'secondmember','class' => 'form-control')) !!} 
			
		</div>

		<div class="form-group">
			{!! Form::submit('Split ' , ['class' => 'btn btn-primary form-control']) !!}
		</div>	

	{!! Form::close() !!}
@endsection




